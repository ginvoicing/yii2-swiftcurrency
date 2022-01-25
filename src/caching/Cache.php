<?php
namespace yii\swiftcurrency\caching;

use yii\base\InvalidConfigException;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\db\Connection as SqlConnection;
use yii\swiftcurrency\caching\models\Sql;

class Cache extends BaseObject implements CachingInterface
{
    /**
     * @var string
     */
    public string $tableName = '{{%swiftcurrency_cache}}';

    /**
     * @var array|string|SqlConnection
     */
    public $connection = null;

    /**
     * Log table model
     */
    private string $_model;

    public function init()
    {
        $this->connection = Instance::ensure($this->connection);

        if ($this->connection instanceof SqlConnection) {
            $this->_model = Sql::class;
        } else {
            throw new InvalidConfigException("This connections doesn't support.");
        }
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getConnection(): SqlConnection
    {
        return $this->connection;
    }

    /**
     * @inheritdoc
     */
    public function setRecord(array $data): bool
    {
        $record = new $this->_model();
        foreach ($data as $key => $value) {
            if ($record->hasAttribute($key)) {
                $record->$key = $value;
            }
        }
        return $record->save();
    }

    /**
     * @inheritdoc
     */
    /*public function updateRecordBySmsId($response_id, $data)
    {
        if (!empty($response_id)) {
            $record = new $this->_model();
            $record = $record->findOne(['response_id' => $response_id]);
            if ($record) {
                foreach ($data as $key => $value) {
                    if ($record->hasAttribute($key)) {
                        $record->$key = $value;
                    }
                }

                return $record->save();
            }
        }

        return false;
    }*/

    /**
     * @inheritdoc
     */
    /*public function updateRecordBySmsIdAndPhone($response_id, $phone, $data)
    {
        if (!empty($sms_id)) {
            $record = new $this->_model();
            $record = $record->findOne(['response_id' => $response_id, 'phone' => $phone]);
            if ($record) {
                foreach ($data as $key => $value) {
                    if ($record->hasAttribute($key)) {
                        $record->$key = $value;
                    }
                }

                return $record->save();
            }
        }

        return false;
    }*/
}
