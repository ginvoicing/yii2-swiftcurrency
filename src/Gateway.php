<?php

namespace yii\swiftcurrency;

use linslin\yii2\curl\Curl;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\swiftcurrency\caching\Cache;
use yii\swiftcurrency\caching\CachingInterface;
use yii\swiftcurrency\enum\Status;
use yii\swiftcurrency\exceptions\RatePullException;

class Gateway extends Component
{
    public array $providers = [];
    public string $baseCurrency;
    public ?array $caching = null;
    public string $tmpPath = '/tmp';

    private ?CachingInterface $_cachingProvider;


    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        if (!$this->providers) {
            throw new InvalidConfigException('Property "providers" is mandatory for swiftcurrency component.');
        }

        if ($this->caching) {
            if (!isset($this->caching['connection']) || empty($this->caching['connection']) ||
                (is_array($this->caching['connection']) && count($this->caching['connection']) === 0)
            ) {
                throw new InvalidConfigException('Logging connection must be set.');
            }
            if (!isset($this->caching['class']) || empty($this->caching['class'])) {
                $this->caching['class'] = Cache::class;
            }

            $this->_cachingProvider = \Yii::createObject($this->caching);
        }
    }

    /**
     * Select provider to be used to pull currency property
     * This property is not read only property for this component.
     *
     * @return ProviderInterface
     * @throws InvalidConfigException
     */
    public function getProvider(): ProviderInterface
    {
        $curlObject = new Curl();
        // useragent for the gateway calls.
        $curlObject->setOption(CURLOPT_USERAGENT, 'yii2-swiftcurrency');


        // get executed requests.
        $usedQuota = $this->getCachingProvider()->getUsedQuota();
        $ctr = 0;
        $dejectedCounter = 0;
        foreach ($this->providers as $givenProvider) {
            if (!file_exists($this->tmpPath . '/' . $givenProvider['class'])) {
                $selectedProvider = \Yii::createObject($givenProvider, [$curlObject]);
                $selectionPointerName = $this->tmpPath . '/' . $selectedProvider->getName();
                if (isset($usedQuota[$selectedProvider->getName()])) {
                    if ($selectedProvider->getMonthlyQuota() >= $usedQuota[$selectedProvider->getName()]) {
                        touch($selectionPointerName);
                        break;
                    } else {
                        $dejectedCounter++;
                    }
                }
            } else {
                $ctr++;
            }
        }
        $totalEligibleProviders = count($this->providers)-$dejectedCounter;
        if ($ctr===$totalEligibleProviders) {
            $pointers = glob($this->tmpPath . '/yii*');
            array_walk($pointers, function ($file) {
                unlink($file);
            });
            return $this->getProvider();
        }

//        do {
//            $selectedProvider = \Yii::createObject($this->providers[$i], [$curlObject]);
//            $previousProviderName = $this->tmpPath . '/' . $selectedProvider->getName();
//        } while (file_exists($previousProviderName) || $selectedProvider->getMonthlyQuota() <= $usedQuota[$selectedProvider->getName()]);

//        touch($selectionPointerName);

        // get available quota.
        // if executed requests are less than quota only than make the provider eligible to be picked.
        // avoid last picked gateway
        return $selectedProvider;
    }


    /**
     * Get exchange rates.
     * @return Response
     * @throws RatePullException
     */

    public function pullRatesFromProvider(): Response
    {
        $response = $this->getProvider()->getExchangeRates($this->baseCurrency);
        if ($this->caching && $response && $response->getStatus() == Status::SUCCESS()) {
            $properties = [
                'timeline' => $response->getTimeLine()->format('Y-m-d H:i:s'),
                'base_currency' => $response->getBaseCurrency(),
                'provider' => $response->getProvider(),
                'raw' => $response->getRaw()
            ];
            $objectToBeSaved = array_merge($properties, $response->getExchangeRates());

            $this->getCachingProvider()->setRecord($objectToBeSaved);
        }

        return $response;
    }

    /**
     * Get balance calls from the provider.
     *
     * @return int
     * @throws exceptions\BalanceException
     */
    public function getBalance(): int
    {
        return $this->getProvider()->getBalance();
    }

    public function getCachingProvider(): ?CachingInterface
    {
        if ($this->_cachingProvider instanceof CachingInterface) {
            return $this->_cachingProvider;
        }

        return null;
    }
}
