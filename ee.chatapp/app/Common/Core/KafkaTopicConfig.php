<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 11/6/2022
 * Time: 20:02
 */

namespace App\Common\Core;


class KafkaTopicConfig
{
    protected $name;
    protected $partitions;
    protected $configs;

    /**
     * KafkaTopicConfig constructor.
     * @param string $name
     * @param KafkaPartitionConfig[] $partitions
     * @param string $offsetReset
     * @param int $commitInterval
     */
    public function __construct(string $name, $partitions, string $offsetReset = 'earliest', int $commitInterval = 500)
    {
        $this->name = $name;
        $this->partitions = $partitions;
        $this->configs = [
            'auto.offset.reset' => $offsetReset,
            'auto.commit.interval.ms' => $commitInterval
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return KafkaPartitionConfig[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param KafkaPartitionConfig[] $partitions
     */
    public function setPartitions($partitions): void
    {
        $this->partitions = $partitions;
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'partitions' => $this->partitions,
            'configs' => $this->configs,
        ];
    }
}