<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/8/2022
 * Time: 10:35
 */

namespace App\Common\Exceptions;


class ObjectInvalidCastException extends \Exception
{

    /**
     * @param $className
     * @param string|null $message
     * @param int|null $code
     * @param \Throwable|null $throwable
     */
    public function __construct($className, string $message = null, $code = 0, \Throwable $throwable = null)
    {
        parent::__construct($className . ($message ?? ' class was not matched'), $code, $throwable);
    }
}