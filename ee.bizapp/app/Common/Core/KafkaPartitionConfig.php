<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 11/6/2022
 * Time: 20:04
 */

namespace App\Common\Core;


class KafkaPartitionConfig
{
    protected $partition;
    protected $offset;

    public function __construct($partition, $offset)
    {
        if ($offset != RD_KAFKA_OFFSET_STORED
            && $offset != RD_KAFKA_OFFSET_BEGINNING
            && $offset != RD_KAFKA_OFFSET_INVALID
            && $offset != RD_KAFKA_OFFSET_END
        ) {
            throw new \InvalidArgumentException('offset invalid');
        }
        $this->partition = $partition;
        $this->offset = $offset;
    }

    /**
     * @param int $partition
     * @param int $offset
     * @return KafkaPartitionConfig
     */
    public static function create($partition = 0, $offset = RD_KAFKA_OFFSET_STORED)
    {
        return new KafkaPartitionConfig($partition, $offset);
    }

    /**
     * @return int
     */
    public function getPartition(): int
    {
        return $this->partition;
    }

    /**
     * @param int $partition
     */
    public function setPartition(int $partition): void
    {
        $this->partition = $partition;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function toArray()
    {
        return (array)$this;
    }
}