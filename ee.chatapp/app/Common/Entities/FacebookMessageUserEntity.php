<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/22/2022
 * Time: 13:53
 */

namespace App\Common\Entities;


class FacebookMessageUserEntity
{
    protected $id;
    protected $user_ref;

    /**
     * FacebookMessageSenderEntity constructor.
     * @param $sender
     */
    public function __construct($sender)
    {
        $this->id = $sender['id'] ?? null;
        $this->user_ref = $sender['user_ref'] ?? null;
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
    public function getUserRef()
    {
        return $this->user_ref;
    }

    /**
     * @param mixed $user_ref
     */
    public function setUserRef($user_ref)
    {
        $this->user_ref = $user_ref;
    }

}