<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageReadEntity
{
    protected $watermark;

    /**
     * FacebookMessageReadEntity constructor.
     * @param $read
     */
    public function __construct($read)
    {
        $this->watermark = $read['watermark'] ?? null;
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