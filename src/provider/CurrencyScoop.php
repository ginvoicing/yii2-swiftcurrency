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


    private string $_baseApi = 'https://api.currencybeacon.com/v1';
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
        if (defined('YII_DEBUG') && YII_DEBUG) {
            \Yii::trace("CurrencyScoop Endpoint: {$this->_baseApi}/latest?app_key={$this->apiKey}");
        }
        $rawResponse = $this->_curl
            ->reset()
            ->get("{$this->_baseApi}/latest?api_key={$this->apiKey}");

        switch ($this->_curl->responseCode) {
            case 429:
                throw new RatePullException('{"status":"FAILED","message": "Too many requests. API limits reached.","output": null}');
            case 200:
                $decodedResponse = json_decode($rawResponse, true);
                $timelineObject = new \DateTime($decodedResponse['response']['date']);
                return (new Response())
                    ->setBaseCurrency($decodedResponse['response']['base'])
                    ->setTimeLine($timelineObject)
                    ->setExchangeRates($decodedResponse['response']['rates'])
                    ->setRaw($rawResponse)
                    ->setProvider(get_class($this))
                    ->setStatus(Status::SUCCESS());
                break;
            case 401:
                throw new RatePullException('{"status":"FAILED","message": "Unauthorized Missing or incorrect API token in header.","output": null}');
            case 500:
                throw new RatePullException('{"status":"FAILED","message": "Internal Server Error This is an issue with Currencyscoop\'s servers processing your request. In most cases the message is lost during the process, and we are notified so that we can investigate the issue.","output": null }');
            case 401:
                throw new RatePullException('{"status":"FAILED","message": "Unauthorized Missing or incorrect API token in header.","output": null}');
            default:
                throw new RatePullException('{"status":"FAILED","message": "Unknown error.","output": null}');
        }
    }
}
