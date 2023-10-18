<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:52
 */

namespace App\Common\Entities;


class FacebookMessageEventEntity
{
    protected $sender;
    protected $recipient;
    protected $timestamp;
    protected $message;
    protected $delivery;
    protected $read;

    /**
     * FacebookMessageEventEntity constructor.
     * @param $event
     */
    public function __construct($event)
    {
        $this->sender = isset($event['sender']) ? new FacebookMessageUserEntity($event['sender']) : null;
        $this->recipient = isset($event['recipient']) ? new FacebookMessageUserEntity($event['recipient']) : null;
        $this->timestamp = $event['timestamp'] ?? null;
        $this->message = isset($event['message']) ? new FacebookMessageEntity($event['message']) : null;
        $this->read = isset($event['read']) ? new FacebookMessageReadEntity($event['read']) : null;
        $this->delivery = isset($event['delivery']) ? new FacebookMessageDeliveryEntity($event['delivery']) : null;
    }

    /**
     * @return FacebookMessageUserEntity
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param FacebookMessageUserEntity $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return FacebookMessageUserEntity
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param FacebookMessageUserEntity $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return FacebookMessageEntity
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param FacebookMessageEntity $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return FacebookMessageDeliveryEntity|null
     */
    public function getDelivery(): FacebookMessageDeliveryEntity
    {
        return $this->delivery;
    }

    /**
     * @param FacebookMessageDeliveryEntity|null $delivery
     */
    public function setDelivery(FacebookMessageDeliveryEntity $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return FacebookMessageReadEntity|null
     */
    public function getRead(): FacebookMessageReadEntity
    {
        return $this->read;
    }

    /**
     * @param FacebookMessageReadEntity|null $read
     */
    public function setRead(FacebookMessageReadEntity $read)
    {
        $this->read = $read;
    }


}