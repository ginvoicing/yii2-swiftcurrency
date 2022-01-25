<?php

class GatewayTest extends Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    protected function _before(): void
    {
        Yii::$app->get('db',)->dsn = 'mysql:host=127.0.0.1;port=' . $_ENV['MYSQL_PORT'] . ';dbname=currencydb';
        Yii::$app->set('swiftcurrency', [
            'class' => \yii\swiftcurrency\Gateway::class,
            'baseCurrency' => 'USD',
            'caching' => [
                'connection' => 'db',
                'tableName' => 'ginni_currency_exchange_rates'
            ],
            'providers' => [
                [
                    'class' => \yii\swiftcurrency\provider\CurrencyScoop::class,
                    'apiKey' => $_ENV['CURRENCY_SCOOP_API'],
                ],
            ]
        ]);
        parent::_before();
    }

    public function testMysqlConnection()
    {
        \Yii::$app->runAction('migrate/up', [
            'interactive' => 0
        ]);
    }

    public function testInvalidConfigurationException()
    {
        Yii::$app->set('swiftcurrency', [
            'class' => \yii\swiftcurrency\Gateway::class
        ]);

    }

    public function testUnknownPropertyException()
    {
        $this->assertThrows(\yii\base\UnknownPropertyException::class, function () {
            \Yii::$app->get('swiftcurrency')->unknown;
        });
    }

    public function testExchangeRates()
    {
        $this->assertTrue(\Yii::$app->swiftcurrency->rates->getStatus() == \yii\swiftcurrency\enum\Status::SUCCESS());
    }
}
