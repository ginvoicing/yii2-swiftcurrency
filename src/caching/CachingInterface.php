<?php
namespace yii\swiftcurrency\caching;

interface CachingInterface
{
    /**
     * Add new record to log
     *
     * @param array $data
     * @return bool
     */
    public function setRecord(array $data): bool;

    /**
     * Update log record by response_id
     *
     * @param string $response_id
     * @param array $data
     * @return bool
     */
//    public function updateRecordBySmsId($response_id, $data);

    /**
     * Update log record by response_id and recipient phone number
     *
     * @param string $response_id
     * @param string $phone
     * @param array $data
     * @return bool
     */
//    public function updateRecordBySmsIdAndPhone($response_id, $phone, $data);
}
