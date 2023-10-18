<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/16/2022
 * Time: 09:28
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DynamicTableColumnTypeRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, [
            'text',
            'number',
            'textarea',
            'html',
            'image',
            'file'
        ]);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return 'Loại trường không hỗ trợ';
    }
}