<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 14:25
 */

namespace App\Common\Entities;


class FacebookMessageProductEntity
{
    protected $id;
    protected $retailer_id;
    protected $image_url;
    protected $title;
    protected $subtitle;

    /**
     * FacebookMessageProductEntity constructor.
     * @param $product
     */
    public function __construct($product)
    {
        $this->id = $product['id'] ?? null;
        $this->retailer_id = $product['retailer_id'] ?? null;
        $this->image_url = $product['image_url'] ?? null;
        $this->title = $product['title'] ?? null;
        $this->subtitle = $product['subtitle'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRetailerId()
    {
        return $this->retailer_id;
    }

    /**
     * @param mixed $retailer_id
     */
    public function setRetailerId($retailer_id)
    {
        $this->retailer_id = $retailer_id;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * @param mixed $image_url
     */
    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
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
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }


}