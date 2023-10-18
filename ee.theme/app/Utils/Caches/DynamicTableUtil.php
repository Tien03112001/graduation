<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/4/2022
 * Time: 15:53
 */

namespace App\Utils\Caches;


use App\Models\DynamicTable;
use App\Repository\DynamicTableRepositoryInterface;

class DynamicTableUtil extends AbstractCacheDataUtil
{

    /**
     * @return DynamicTableUtil
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function __construct()
    {
        parent::__construct('dynamic_tables', DynamicTableRepositoryInterface::class, DynamicTable::class);
    }


    public function loadCacheData()
    {
        if ($this->repository instanceof DynamicTableRepositoryInterface) {
            $tables = $this->repository->get([], null, ['columns', 'rows.cells']);
            foreach ($tables as $table) {
                if ($table instanceof DynamicTable) {
                    $tableData = [];
                    foreach ($table->rows as $row) {
                        $rowData = [
                            'id' => $row->id
                        ];
                        $mapColumns = [];
                        foreach ($table->columns as $column) {
                            $rowData[$column->name] = null;
                            $mapColumns[$column->id] = $column->name;
                        }
                        foreach ($row->cells as $cell) {
                            $rowData[$mapColumns[$cell->column_id]] = $cell->cell_value;
                        }
                        array_push($tableData, $rowData);
                    }
                    $this->data[$table->name] = $tableData;
                }
            }
        }
    }

    public function cacheData()
    {
        return parent::cacheKeyValueData();
    }

}