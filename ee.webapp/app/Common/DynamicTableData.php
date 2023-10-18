<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 5/26/2022
 * Time: 14:47
 */

namespace App\Common;


class DynamicTableData
{
    protected $id;
    protected $rows;

    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows(array $rows): void
    {
        $this->rows = $rows;
    }


}