<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 3/9/2023
 * Time: 19:58
 */

namespace App\Common\SEO;


use App\Models\MetaData;
use App\Models\StructureData;
use App\Utils\StructureDataUtil;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\URL;

class SEOPage
{
    public $name;

    public $slug;

    public $amp_path;

    /* @var MetaData */
    public $meta;
    /* @var string[] */
    public $structures;

    /**
     * SEOPage constructor.
     * @param string $name
     * @param MetaData|null $meta_data
     * @param StructureData[]|Collection $structures
     * @param null $amp_path
     */
    public function __construct(string $name, MetaData $meta_data = null, $structures = [], $amp_path = null)
    {
        $this->name = $name;
        $this->slug = URL::current();
        $this->amp_path = $amp_path;
        $this->meta = $meta_data ?? new MetaData(['title' => $name]);
        $this->structures = [];
        $sd = StructureDataUtil::getInstance()->getOrganization();
        if (isset($sd)) {
            array_push($this->structures, $sd);
        }
        $sd = StructureDataUtil::getInstance()->getLocalBusiness();
        if (isset($sd)) {
            array_push($this->structures, $sd);
        }
        foreach ($structures as $st) {
            array_push($structures, $st->content);
        }

    }

    public function pushStructure(string $structureData)
    {
        array_push($this->structures, $structureData);
    }

}