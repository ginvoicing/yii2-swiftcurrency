<?php

namespace yii\swiftcurrency\provider;

use yii\swiftcurrency\enum\Status;
use yii\swiftcurrency\exceptions\BalanceException;
use yii\swiftcurrency\exceptions\RatePullException;
use yii\swiftcurrency\ProviderInterface;
use yii\swiftcurrency\Response;

class OpenExchangeRates extends Base implements ProviderInterface
{
    public string $apiKey;


    private string $_baseApi = 'https://openexchangerates.org/api';
    protected int $monthlyQuota = 1000;
    protected array $responseCodesMap = [
        404 => 'Client requested a non-existent resource/route.',
        401 => 'Client did not provide an App ID.',
        429 => 'Client doesn’t have permission to access requested route/feature.',
        403 => 'Access restricted for repeated over-use (status: 429), or other reason given in ‘description’ (403).',
        400 => 'Client requested rates for an unsupported base currency.',
    ];

    public function getBalance(): int
    {
        $rawResponse = null;
        $thisProvider = get_class($this);
        throw new BalanceException('{"status":"FAILED","message": "No such stats are available in ' .
            $thisProvider .
            ' provider.","output": "' .
            $rawResponse .
            '"}');
    }

    public function getExchangeRates(string $baseCurrency): Response
    {
        if (defined('YII_DEBUG') && YII_DEBUG) {
            \Yii::trace("OpenExchangeRate Endpoint: {$this->_baseApi}/latest.json?app_id={$this->apiKey}");
        }
        $rawResponse = $this->_curl
            ->reset()
            ->get("{$this->_baseApi}/latest.json?app_id={$this->apiKey}");
        switch ($this->_curl->responseCode) {
            case 'timeout':
                throw new RatePullException('{"status":"FAILED","message": "Connection timeout.","output": null}');
            case 200:
                $decodedResponse = json_decode($rawResponse, true);
                $timelineObject = new \DateTime();
                return (new Response())
                    ->setBaseCurrency($decodedResponse['base'])
                    ->setTimeLine($timelineObject)
                    ->setExchangeRates($decodedResponse['rates'])
                    ->setRaw($rawResponse)
                    ->setProvider(get_class($this))
                    ->setStatus(Status::SUCCESS());
                break;
            case 404:
                throw new RatePullException('{"status":"FAILED","message": "Connection problem with the gateway.","output": null}');
            default:
                throw new RatePullException('{"status":"FAILED","message": "Unknown error.","output": null}');
        }
    }
}
