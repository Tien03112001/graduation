<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 11/7/2022
 * Time: 23:27
 */

namespace App\Common\Config;


class AccountConfig
{
    protected $owner_name;
    protected $bank_name;
    protected $bank_account;
    protected $bank_branch;


    public function __construct(array $account)
    {
        $this->owner_name = $account['owner_name'];
        $this->bank_name = $account['bank_name'];
        $this->bank_account = $account['bank_account'];
        $this->bank_branch = $account['bank_branch'];
    }

    /**
     * @return mixed
     */
    public function getOwnerName()
    {
        return $this->owner_name;
    }

    /**
     * @param mixed $owner_name
     */
    public function setOwnerName($owner_name): void
    {
        $this->owner_name = $owner_name;
    }

    /**
     * @return mixed
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * @param mixed $bank_name
     */
    public function setBankName($bank_name): void
    {
        $this->bank_name = $bank_name;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * @param mixed $bank_account
     */
    public function setBankAccount($bank_account): void
    {
        $this->bank_account = $bank_account;
    }

    /**
     * @return mixed
     */
    public function getBankBranch()
    {
        return $this->bank_branch;
    }

    /**
     * @param mixed $bank_branch
     */
    public function setBankBranch($bank_branch): void
    {
        $this->bank_branch = $bank_branch;
    }

}