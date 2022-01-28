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

    public function __get(string $name): string
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
