<?php

namespace yii\swiftcurrency;

use yii\swiftcurrency\exceptions\BalanceException;
use yii\swiftcurrency\exceptions\RatePullException;

interface ProviderInterface
{
    /**
     * Get sms balance in user's account
     *
     * @return mixed
     * @throws BalanceException
     */
    public function getBalance();

    /**
     * Pull exchange rates given by the provider api.
     *
     * @return mixed
     * @throws RatePullException
     */
    public function getExchangeRates(string $baseCurrency): Response;
}
