<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 7/25/2022
 * Time: 19:15
 */

namespace App\Common\Enum;


class EziWebRemoteCommandEnum
{
    const CMD_GIT_PULL = 'git pull';
    const CMD_OPTIMIZE = 'php artisan optimize';
    const CMD_OPTIMIZE_CLEAR = 'php artisan optimize:clear';
    const CMD_UP = 'php artisan up';
    const CMD_DOWN = 'php artisan down';

    const CMD_AUTO_ENABLE = 'php artisan auto:enable';
    const CMD_AUTO_GEN = 'php artisan auto:gen';

}