<?php

namespace yii\swiftcurrency;

use yii\swiftcurrency\enum\Status;
use \DateTime;

final class Response
{
    private ?string $raw = null;
    private string $status;
    private DateTime $timeLine;
    private string $baseCurrency = 'USD';
    private string $exchangeRates;
    private string $provider;

    public function __construct()
    {
        $this
            ->setStatus(Status::PENDING())
            ->setRaw('{"status":"ERROR", "message":"Connectivity problem."}');
    }

    /**
     * Get date of the response. You may get in the requested api.
     * @return DateTime
     */
    public function getTimeLine(): DateTime
    {
        return $this->timeLine;
    }

    public function setTimeLine(DateTime $timeLine): Response
    {
        $this->timeLine = $timeLine;
        return $this;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function setBaseCurrency(string $baseCurrency): Response
    {
        $this->baseCurrency = $baseCurrency;
        return $this;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): Response
    {
        $this->raw = $raw;
        return $this;
    }

    public function setStatus(string $status): Response
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setExchangeRates(array $exchangeRates): Response
    {
        $this->exchangeRates = json_encode($exchangeRates);
        return $this;
    }

    public function getExchangeRates(): array
    {
        return json_decode($this->exchangeRates, true);
    }

    public function setProvider(string $provider): Response
    {
        $this->provider = $provider;
        return $this;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
