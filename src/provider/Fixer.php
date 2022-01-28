<?php

namespace yii\swiftcurrency\provider;

use yii\swiftcurrency\enum\Status;
use yii\swiftcurrency\exceptions\BalanceException;
use yii\swiftcurrency\exceptions\RatePullException;
use yii\swiftcurrency\ProviderInterface;
use yii\swiftcurrency\Response;

class Fixer extends Base implements ProviderInterface
{
    public string $apiKey;


    protected int $monthlyQuota = 100;
    private string $_baseApi = 'http://data.fixer.io/api';
    protected array $responseCodesMap = [
        404 => 'The requested resource does not exist.',
        403 => 'No or an invalid amount has been specified. [convert]',
        101 => 'No API Key was specified or an invalid API Key was specified.',
        102 => 'The account this API request is coming from is inactive.',
        103 => 'The requested API endpoint does not exist.',
        104 => 'The maximum allowed API amount of monthly API requests has been reached.',
        105 => 'The current subscription plan does not support this API endpoint.',
        106 => 'The current request did not return any results.',
        201 => 'An invalid base currency has been entered.',
        202 => 'One or more invalid symbols have been specified.',
        301 => 'No date has been specified. [historical]',
        302 => 'An invalid date has been specified. [historical, convert]',
        501 => 'No or an invalid timeframe has been specified. [timeseries]',
        502 => 'No or an invalid "start_date" has been specified. [timeseries, fluctuation]',
        503 => 'No or an invalid "end_date" has been specified. [timeseries, fluctuation]',
        504 => 'An invalid timeframe has been specified. [timeseries, fluctuation]',
        505 => 'The specified timeframe is too long, exceeding 365 days. [timeseries, fluctuation]'
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
        $rawResponse = $this->_curl
            ->reset()
            ->get("{$this->_baseApi}/latest?access_key={$this->apiKey}");
        if ($rawResponse == null) {
            throw new RatePullException('{"status":"FAILED","message": "Connection problem with the gateway.","output": null}');
        }
        $decodedResponse = json_decode($rawResponse, true);
        $timelineObject = new \DateTime();
        return (new Response())
            ->setBaseCurrency($decodedResponse['base'])
            ->setTimeLine($timelineObject)
            ->setExchangeRates($decodedResponse['rates'])
            ->setRaw($rawResponse)
            ->setProvider(get_class($this))
            ->setStatus(Status::SUCCESS());
    }
}
