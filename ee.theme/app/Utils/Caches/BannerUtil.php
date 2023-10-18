<?php 
 
/** 
 * Created by PhpStorm. 
 * User: BaoHoang 
 * Date: 5/26/2022 
 * Time: 12:15 
 */ 
 
namespace App\Utils\Caches; 
 
 
use App\Common\Exceptions\ObjectInvalidCastException; 
use App\Models\BannerGroup; 
use App\Repository\BannerGroupRepositoryInterface; 
 
class BannerUtil extends AbstractCacheDataUtil 
{ 
 
    public static function getInstance() 
    { 
        return parent::getInstance(); 
    } 
 
    public function __construct() 
    { 
        parent::__construct('banners', BannerGroupRepositoryInterface::class, BannerGroup::class); 
    } 
 
    public function loadCacheData() 
    { 
        if ($this->repository instanceof BannerGroupRepositoryInterface) { 
            $models = $this->repository->get([], 'id:asc', ['banners']); 
            foreach ($models as $model) { 
                if ($model instanceof BannerGroup) { 
                    $banners = []; 
                    foreach ($model->banners as $m) { 
                        array_push($banners, $m->toArray()); 
                    } 
                    $this->data[$model->name] = $banners; 
                } else { 
                    throw new ObjectInvalidCastException(BannerGroup::class); 
                } 
            } 
        } else { 
            throw new ObjectInvalidCastException(BannerGroupRepositoryInterface::class); 
        } 
    } 
 
}