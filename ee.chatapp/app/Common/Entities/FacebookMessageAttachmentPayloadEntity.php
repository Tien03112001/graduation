<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageAttachmentPayloadEntity
{
    protected $url;
    protected $title;
    protected $sticker_id;
    protected $product;

    /**
     * FacebookMessageAttachmentPayloadEntity constructor.
     * @param $payload
     */
    public function __construct($payload)
    {
        $this->url = $payload['url'] ?? null;
        $this->title = $payload['title'] ?? null;
        $this->sticker_id = $payload['sticker_id'] ?? null;
        if (isset($payload['product']['elements'])) {
            $this->product = new FacebookMessageProductEntity($payload['product']['elements']);
        }
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getStickerId()
    {
        return $this->sticker_id;
    }

    /**
     * @param mixed $sticker_id
     */
    public function setStickerId($sticker_id)
    {
        $this->sticker_id = $sticker_id;
    }

    /**
     * @return FacebookMessageProductEntity
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param FacebookMessageProductEntity $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }


}