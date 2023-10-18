<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageEntity
{
    protected $mid;
    protected $text;
    protected $quick_reply;
    protected $reply_to;
    protected $attachments;
    protected $referral;

    /**
     * FacebookMessageEntity constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->mid = $message['mid'] ?? null;
        $this->text = $message['text'] ?? null;
        $this->quick_reply = isset($message['quick_reply']) ? $message['quick_reply']['payload'] : null;
        $this->reply_to = isset($message['reply_to']) ? $message['reply_to']['mid'] : null;
        $this->attachments = [];
        if (isset($message['attachments'])) {
            foreach ($message['attachments'] as $attachment) {
                array_push($this->attachments, new FacebookMessageAttachmentEntity($attachment));
            }
        }
        $this->referral = isset($message['referral']) ? new FacebookMessageProductEntity($message['referral']['product']) : null;
    }

    /**
     * @return mixed
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param mixed $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getQuickReply()
    {
        return $this->quick_reply;
    }

    /**
     * @param mixed $quick_reply
     */
    public function setQuickReply($quick_reply)
    {
        $this->quick_reply = $quick_reply;
    }

    /**
     * @return mixed
     */
    public function getReplyTo()
    {
        return $this->reply_to;
    }

    /**
     * @param mixed $reply_to
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    /**
     * @return FacebookMessageAttachmentEntity[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param FacebookMessageAttachmentEntity[] $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @return FacebookMessageProductEntity
     */
    public function getReferral()
    {
        return $this->referral;
    }

    /**
     * @param FacebookMessageProductEntity $referral
     */
    public function setReferral($referral)
    {
        $this->referral = $referral;
    }

}