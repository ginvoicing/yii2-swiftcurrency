<?php

namespace yii\swiftcurrency\provider;

use linslin\yii2\curl\Curl;
use yii\base\UnknownPropertyException;

abstract class Base
{
    protected $_attributes;
    protected $_curl;
    protected int $monthlyQuota;
    protected array $responseCodesMap;

    public function __construct(Curl $curl, $config = [])
    {
        $this->_curl = $curl;
        $this->_attributes = $config;
    }

    /**
     * Returns the curl client cleared for a new request.
     * The previous handle is dropped before reset() so that linslin/yii2-curl
     * does not call curl_close(), which is deprecated since PHP 8.5.
     *
     * @return Curl
     */
    protected function resetCurl(): Curl
    {
        $this->_curl->curl = null;
        return $this->_curl->reset();
    }

    public function __get(string $name): mixed
    {
        if (!isset($this->_attributes[$name])) {
            throw new UnknownPropertyException("\"{$name}\" is an invalid property.", 210419831);
        }
        return $this->_attributes[$name];
    }

    public function getMonthlyQuota(): int
    {
        return $this->monthlyQuota;
    }

    public function getResponseMessage(int $code): ?array
    {
        return $this->responseCodesMap[$code] ?? null;
    }

    public function getName(): string
    {
        return get_class($this);
    }
}
