<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/16/2022
 * Time: 09:28
 */

namespace App\Rules;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class MenuableTypeRule implements Rule
{
    protected $attribute;
    protected $value;


    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->value = $value;
        $this->attribute = $attribute;
        return in_array(Str::lower($value), [
            'manual',
            (new Product())->getTable(),
            (new ProductCategory())->getTable(),
            (new Post())->getTable(),
            (new PostCategory())->getTable(),
            (new Page())->getTable(),
        ]);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return "$this->attribute không hỗ trợ giá trị $this->value";
    }
}