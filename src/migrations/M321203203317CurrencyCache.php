<?php
/**
 * Created by PhpStorm.
 * User: tarunjangra
 * Date: 01/02/2021
 * Time: 08:15
 */

namespace yii\swiftcurrency\migrations;

use \yii\swiftcurrency\caching\Cache;
use yii\swiftcurrency\exceptions\BadCachingProvider;

class M321203203317CurrencyCache extends \yii\db\Migration
{
    public $tableName = '{{%swiftcurrency_cache}}';

    public function init()
    {
        /**
         * @var Cache $cacheProvider
         */
        $cacheProvider = \Yii::$app->swiftcurrency->getCachingProvider();
        if ($cacheProvider === false) {
            throw new BadCachingProvider('Caching agent is required.');
        }
        $this->tableName = $cacheProvider->getTableName();
        $this->db = $cacheProvider->getConnection();
    }

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'timeline' => $this->timestamp()->defaultExpression('NOW()'),
            'base_currency' => $this->string(3)->notNull(),
            'exchange_rates' => $this->json()->notNull(),
            'raw' => $this->text(),
            'provider' => $this->string(100),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
