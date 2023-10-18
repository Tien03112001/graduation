<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageAttachmentEntity
{
    protected $type;
    protected $payload;

    /**
     * FacebookMessageAttachmentEntity constructor.
     * @param $attachment
     */
    public function __construct($attachment)
    {
        $this->type = $attachment['type'];
        $this->payload = new FacebookMessageAttachmentPayloadEntity($attachment['payload']);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return FacebookMessageAttachmentPayloadEntity
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param FacebookMessageAttachmentPayloadEntity $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }


}