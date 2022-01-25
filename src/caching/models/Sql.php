<?php
namespace yii\swiftcurrency\caching\models;

use yii\db\ActiveRecord;

class Sql extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->swiftcurrency->getCachingProvider()->getConnection();
    }

    public static function tableName(): string
    {
        return \Yii::$app->swiftcurrency->getCachingProvider()->getTableName();
    }

    public function rules()
    {
        return [
            [['timeline', 'base_currency', 'exchange_rates'], 'required'],
            [['provider'], 'string', 'max' => 100]
        ];
    }
}
