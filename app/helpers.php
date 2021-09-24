<?php

use Illuminate\Contracts\Auth\Authenticatable;

/**
 *  Return the current auth user.
 * 
 *  @return Illuminate\Contracts\Auth\Authenticatable|null
 */
if (!function_exists('current_user'))
{
    function current_user() : Authenticatable|null
    {
        return auth()->user();
    }
}