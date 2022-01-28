<?php

namespace yii\swiftcurrency\provider;

use yii\swiftcurrency\enum\Status;
use yii\swiftcurrency\exceptions\BalanceException;
use yii\swiftcurrency\exceptions\RatePullException;
use yii\swiftcurrency\ProviderInterface;
use yii\swiftcurrency\Response;

class CurrencyScoop extends Base implements ProviderInterface
{
    public string $apiKey;


    private string $_baseApi = 'https://api.currencyscoop.com/v1';
    protected int $monthlyQuota = 5000;
    protected array $responseCodesMap = [
        200 => 'Success Everything went smooth.',
        400 => 'Unauthorized Missing or incorrect API token in header.',
        422 => 'Unprocessable Entity meaning something with the message isn’t quite right, this could be malformed JSON or incorrect fields. In this case, the response body contains JSON with an API error code and message containing details on what went wrong.',
        500 => 'Internal Server Error This is an issue with Currencyscoop\'s servers processing your request. In most cases the message is lost during the process, and we are notified so that we can investigate the issue.',
        503 => 'Service Unavailable During planned service outages, Currencyscoop API services will return this HTTP response and associated JSON body.',
        429 => 'Too many requests. API limits reached.',
        600 => 'Maintenance - The Currencyscoop API is offline for maintenance.',
        601 => 'Unauthorized Missing or incorrect API token.',
        602 => 'Invalid query parameters.',
        603 => 'Authorized Subscription level required.'
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
            ->get("{$this->_baseApi}/latest?api_key={$this->apiKey}");
        if ($rawResponse == null) {
            throw new RatePullException('{"status":"FAILED","message": "Connection problem with the gateway.","output": null}');
        }
        $decodedResponse = json_decode($rawResponse, true);
        $timelineObject = new \DateTime($decodedResponse['response']['date']);
        return (new Response())
            ->setBaseCurrency($decodedResponse['response']['base'])
            ->setTimeLine($timelineObject)
            ->setExchangeRates($decodedResponse['response']['rates'])
            ->setRaw($rawResponse)
            ->setProvider(get_class($this))
            ->setStatus(Status::SUCCESS());
    }
}
