<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageDeliveryEntity
{
    protected $mids;
    protected $watermark;

    /**
     * FacebookMessageDeliveryEntity constructor.
     * @param $delivery
     */
    public function __construct($delivery)
    {
        $this->mids = $sender['mids'] ?? [];
        $this->watermark = $sender['watermark'] ?? null;
    }

    /**
     * @return array
     */
    public function getMids(): array
    {
        return $this->mids;
    }

    /**
     * @param array $mids
     */
    public function setMids(array $mids)
    {
        $this->mids = $mids;
    }

    /**
     * @return mixed
     */
    public function getWatermark()
    {
        return $this->watermark;
    }

    /**
     * @param mixed $watermark
     */
    public function setWatermark($watermark)
    {
        $this->watermark = $watermark;
    }

}