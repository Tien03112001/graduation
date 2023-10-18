<?php
/**
 * Created by PhpStorm.
 * ProductVariant: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\ProductVariant;
use App\Models\Role;

interface ProductVariantRepositoryInterface extends RepositoryInterface
{
    public function attach(ProductVariant $user, Role $role);
    public function detach(ProductVariant $user, Role $role);
}