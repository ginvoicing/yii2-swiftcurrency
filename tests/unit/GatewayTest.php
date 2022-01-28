<?php

class GatewayTest extends Codeception\Test\Unit
{
    use \Codeception\AssertThrows;

    protected function _before(): void
    {
        Yii::$app->get('db',)->dsn = 'mysql:host=127.0.0.1;port=' . $_ENV['MYSQL_PORT'] . ';dbname=currencydb';
        Yii::$app->set('swiftcurrency', [
            'class' => \yii\swiftcurrency\Gateway::class,
            'baseCurrency' => 'EUR',
            'caching' => [
                'connection' => 'db',
                'tableName' => 'ginni_currency_exchange_rates'
            ],
            'providers' => [
                [
                    'class' => \yii\swiftcurrency\provider\CurrencyScoop::class,
                    'apiKey' => $_ENV['CURRENCY_SCOOP_API']
                ],
                [
                    'class' => \yii\swiftcurrency\provider\Fixer::class,
                    'apiKey' => $_ENV['FIXER_API']
                ],
                [
                    'class' => \yii\swiftcurrency\provider\OpenExchangeRates::class,
                    'apiKey' => $_ENV['OPEN_EXCHANGE_API']
                ],
                [
                    'class' => \yii\swiftcurrency\provider\ExchangeRatesAPI::class,
                    'apiKey' => $_ENV['EXCHANGE_RATE_API']
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


    public function testUnknownPropertyException()
    {
        $this->assertThrows(\yii\base\UnknownPropertyException::class, function () {
            \Yii::$app->get('swiftcurrency')->unknown;
        });
    }

//    public function testNotfoundTransporterException()
//    {
//        Yii::$app->get('swiftsmser')->transporters = [
//
//            [
//                'class' => '\yii\swiftsmser\transporter\Unknown',
//                'type' => \yii\swiftsmser\enum\Type::PROMOTIONAL(),
//                'params' => [
//                    'apiKey' => '32423423'
//                ]
//            ]
//        ];
//        $this->assertThrows(\yii\swiftsmser\exceptions\TransporterNotFoundException::class, function () {
//            \Yii::$app->swiftcurrency->promotional;
//        });
//    }

//    public function testGatewaySelection()
//    {
//        $this->assertInstanceOf(ICloudMessage::class, \Yii::$app->swiftcurrency->promotional->transporter);
//        $this->assertInstanceOf(Biz2::class, \Yii::$app->swiftcurrency->transactional->transporter);
//    }
//
//    public function testBalanceFromPromotionalGateway()
//    {
//        $this->assertIsInt(\Yii::$app->swiftcurrency->promotional->balance, "Valid response from the gateway");
//    }
//
    public function testExchangeRates()
    {
        $this->assertTrue(\Yii::$app->swiftcurrency->pullRatesFromProvider()->getStatus() == \yii\swiftcurrency\enum\Status::SUCCESS());
    }
//
//
//    public function testSendTransactionalSMS()
//    {
//        /** @var \yii\swiftsmser\SMSPacket $smsPacket */
//        $smsPacket = \Yii::createObject([
//            'class' => \yii\swiftsmser\SMSPacket::class,
//            'templateId' => '1107161061671432172',
//            'body' => 'Dear {#var#}, There is an estimate: {#var#} of {#var#}. For more details {#var#} Thank You, {#var#} ginvoicing.com',
//            'variables' => ["Hansika Jangra", "EST-213", "Rs. 45.21", "gnvc.in/iud2", "universal Communication"],
//            'to' => ['9888300750']
//        ]);
//
//        /** @var \yii\swiftsmser\ResponseInterface $response */
//        $response = \Yii::$app->swiftcurrency->transactional->send($smsPacket);
//        $this->assertTrue($response->getStatus() == \yii\swiftsmser\enum\Status::SUCCESS());
//    }
//
//    public function testNonUnicodeDeductionOfSMS()
//    {
//        /** @var \yii\swiftsmser\SMSPacket $smsPacket */
//        $smsPacket = \Yii::createObject([
//            'class' => \yii\swiftsmser\SMSPacket::class,
//            'templateId' => '1107161061671432172',
//            'body' => 'Dear {#var#}, There is a new invoice: {#var#} of {#var#}. For more details {#var#} Thank You, {#var#} ginvoicing.com',
//            'variables' => ["Deepak kumar", "INV-0013", "Rs 344.3", "gnvc.in/a", "HelloCommunication"],
//            'to' => ['9888300750']
//        ]);
//        $this->assertTrue($smsPacket->deduction === 1, "Deduction is {$smsPacket->deduction}");
//    }
//
//    public function testUnicodeDeductionOfSMS()
//    {
//        /** @var \yii\swiftsmser\SMSPacket $smsPacket */
//        $smsPacket = \Yii::createObject([
//            'class' => \yii\swiftsmser\SMSPacket::class,
//            'templateId' => '1107161061671432172',
//            'body' => 'Dear {#var#}, There is a new invoice: {#var#} of {#var#}. For more details {#var#} Thank You, {#var#} ginvoicing.com',
//            'variables' => ["Deepak kumar", "INV-0013", "Rs 344.3", "gnvc.in/a", "हैलो कम्यूनिकेशन।"],
//            'to' => ['9888300750']
//        ]);
//        $this->assertTrue($smsPacket->deduction === 3, "Deduction is {$smsPacket->deduction}");
//    }


    // public function testSendTransactionalSMSWithTemplateAPI()
    // {
    //     /** @var \yii\swiftsmser\ResponseInterface $response */
    //     $response = \Yii::$app->swiftcurrency->transactional->send(
    //          (new \yii\swiftsmser\SMSPacket())
    //              ->setTemplateId('34a7b0e7-58cd-40b5-a3d9-c901948ec33d')
    //              ->setBody(
    //                  "Dear {#var#}, There is an estimate: {#var#} of {#var#}. For more details {#var#} Thank You, {#var#} ginvoicing.com",
    //                  ["Deepak Jangra","EST-004","Rs. 424.11","gnvc.in/34324","Raj Communication"]
    //              )
    //              ->setEntityId('1101147480000010561')
    //              ->setHeaderId('1105158201172710267')
    //              ->setDeduction(2)
    //          , ['9888300750']);
    // }

//    public function testSendPromotionalSMS()
//    {
//        /** @var \yii\swiftsmser\SMSPacket $smsPacket */
//        $smsPacket = \Yii::createObject([
//            'class' => \yii\swiftsmser\SMSPacket::class,
//            'templateId' => '1107161061675566196',
//            'body' => 'Dear {#var#}, There is a new invoice: {#var#} of {#var#}. For more details {#var#} Thank You, {#var#} ginvoicing.com',
//            'variables' => ["Deepak kumar", "INV-0013", "Rs 344.3", "gnvc.in/a", "HelloCommunication"],
//            'to' => ['9888300750']
//        ]);
//
//        $response = \Yii::$app->swiftcurrency->promotional->send($smsPacket);
//        /** @var \yii\swiftsmser\ResponseInterface $response */
//        $this->assertTrue($response->getStatus() == \yii\swiftsmser\enum\Status::SUCCESS());
//    }
//
//    public function testBalanceOfGateways()
//    {
//        $balanceArray = \Yii::$app->swiftcurrency->gatewayBalance;
//        foreach ($balanceArray as $balanceObj) {
//            $this->assertTrue(isset($balanceObj['name']));
//            $this->assertTrue(isset($balanceObj['type']) && ($balanceObj['type'] == \yii\swiftsmser\enum\Type::TRANSACTIONAL() ||
//                $balanceObj['type'] == \yii\swiftsmser\enum\Type::PROMOTIONAL()));
//            $this->assertTrue($balanceObj['credit'] > 0);
//        }
//    }
}
