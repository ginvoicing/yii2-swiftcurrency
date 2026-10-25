<?php

use linslin\yii2\curl\Curl;
use yii\swiftcurrency\enum\Status;
use yii\swiftcurrency\exceptions\RatePullException;
use yii\swiftcurrency\provider\CurrencyScoop;
use yii\swiftcurrency\provider\ExchangeRatesAPI;
use yii\swiftcurrency\provider\Fixer;
use yii\swiftcurrency\provider\OpenExchangeRates;

/**
 * Curl stub that answers without network access. Like the real client it
 * keeps a curl handle open after a request, so a later reset() would close it.
 */
class FakeCurl extends Curl
{
    public int $nextCode = 200;
    public string $nextBody = '';
    public array $requestedUrls = [];

    public function get($url, $raw = true)
    {
        $this->requestedUrls[] = $url;
        $this->curl = curl_init();
        $this->responseCode = $this->nextCode;
        return $this->nextBody;
    }
}

/**
 * Offline tests for the providers. No API keys or database are needed.
 */
class ProviderTest extends Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    private function createProvider(string $class, FakeCurl $curl)
    {
        return \Yii::createObject(['class' => $class, 'apiKey' => 'test-key'], [$curl]);
    }

    public function testResponseDefaultsToPending()
    {
        $this->assertSame(Status::PENDING, (new \yii\swiftcurrency\Response())->getStatus());
    }

    public function testCurrencyScoopRates()
    {
        $curl = new FakeCurl();
        $curl->nextBody = '{"response":{"date":"2026-10-25T00:00:00Z","base":"EUR","rates":{"USD":1.08,"INR":90.1}}}';
        $response = $this->createProvider(CurrencyScoop::class, $curl)->getExchangeRates('EUR');

        $this->assertSame(Status::SUCCESS, $response->getStatus());
        $this->assertSame('EUR', $response->getBaseCurrency());
        $this->assertSame(['USD' => 1.08, 'INR' => 90.1], $response->getExchangeRates());
        $this->assertSame('2026-10-25', $response->getTimeLine()->format('Y-m-d'));
        $this->assertSame(CurrencyScoop::class, $response->getProvider());
        $this->assertSame(['https://api.currencybeacon.com/v1/latest?api_key=test-key'], $curl->requestedUrls);
    }

    public function testRatesFromFixerStyleProviders()
    {
        $body = '{"success":true,"base":"EUR","rates":{"USD":1.08}}';
        foreach ([Fixer::class, ExchangeRatesAPI::class, OpenExchangeRates::class] as $class) {
            $curl = new FakeCurl();
            $curl->nextBody = $body;
            $response = $this->createProvider($class, $curl)->getExchangeRates('EUR');

            $this->assertSame(Status::SUCCESS, $response->getStatus(), $class);
            $this->assertSame(['USD' => 1.08], $response->getExchangeRates(), $class);
            $this->assertSame($body, $response->getRaw(), $class);
        }
    }

    public function testFixerUnsuccessfulResponse()
    {
        $curl = new FakeCurl();
        $curl->nextBody = '{"success":false,"error":{"code":101}}';
        $provider = $this->createProvider(Fixer::class, $curl);

        $this->assertThrows(RatePullException::class, function () use ($provider) {
            $provider->getExchangeRates('EUR');
        });
    }

    public function testErrorResponseCodes()
    {
        $cases = [
            [CurrencyScoop::class, 429],
            [CurrencyScoop::class, 401],
            [CurrencyScoop::class, 500],
            [OpenExchangeRates::class, 404],
            [ExchangeRatesAPI::class, 503],
        ];
        foreach ($cases as [$class, $code]) {
            $curl = new FakeCurl();
            $curl->nextCode = $code;
            $provider = $this->createProvider($class, $curl);

            $this->assertThrows(RatePullException::class, function () use ($provider) {
                $provider->getExchangeRates('EUR');
            });
        }
    }

    /**
     * A provider reuses its curl client, so the second request resets a client
     * that still holds a handle. That must not trigger the curl_close()
     * deprecation of PHP 8.5, which Yii turns into an ErrorException.
     */
    public function testProviderCanBeReused()
    {
        $curl = new FakeCurl();
        $curl->nextBody = '{"success":true,"base":"EUR","rates":{"USD":1.08}}';
        $provider = $this->createProvider(OpenExchangeRates::class, $curl);

        $provider->getExchangeRates('EUR');
        $response = $provider->getExchangeRates('EUR');

        $this->assertSame(Status::SUCCESS, $response->getStatus());
        $this->assertCount(2, $curl->requestedUrls);
    }

    public function testGatewayWithoutCaching()
    {
        $tmpPath = \Yii::getAlias('@tests/_output/pointers');
        \yii\helpers\FileHelper::createDirectory($tmpPath);
        $gateway = new \yii\swiftcurrency\Gateway([
            'baseCurrency' => 'EUR',
            'tmpPath' => $tmpPath,
            'providers' => [
                ['class' => Fixer::class, 'apiKey' => 'test-key'],
            ],
        ]);

        $this->assertNull($gateway->getCachingProvider());
        $this->assertInstanceOf(Fixer::class, $gateway->getProvider());
    }
}
