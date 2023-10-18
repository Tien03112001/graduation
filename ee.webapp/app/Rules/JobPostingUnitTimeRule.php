<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/16/2022
 * Time: 09:28
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class JobPostingUnitTimeRule implements Rule
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
        return in_array($value, [
            'hour',
            'day',
            'month',
            'year',
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