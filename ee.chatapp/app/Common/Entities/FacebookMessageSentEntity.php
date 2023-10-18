<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageSentEntity
{
    protected $recipient_id;
    protected $message_id;

    /**
     * FacebookMessageSentEntity constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $this->recipient_id = $response['recipient_id'] ?? null;
        $this->message_id = $response['message_id'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    /**
     * @param mixed $recipient_id
     */
    public function setRecipientId($recipient_id)
    {
        $this->recipient_id = $recipient_id;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @param mixed $message_id
     */
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
    }


}