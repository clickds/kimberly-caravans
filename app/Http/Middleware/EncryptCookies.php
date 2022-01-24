<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    public const DISMISSED_POP_UPS_COOKIE_NAME = 'dismissedPopUpIds';

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        self::DISMISSED_POP_UPS_COOKIE_NAME,
    ];
}
