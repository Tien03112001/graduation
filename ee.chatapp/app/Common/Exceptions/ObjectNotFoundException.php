<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/8/2022
 * Time: 10:35
 */

namespace App\Common\Exceptions;


class ObjectNotFoundException extends \Exception
{

    /**
     * ObjectNotFoundException constructor.
     * @param string|null $message
     * @param int|null $code
     * @param \Throwable|null $throwable
     */
    public function __construct(string $message = null, $code = 0, \Throwable $throwable = null)
    {
        parent::__construct($message ?? 'Object not found', $code, $throwable);
    }
}