<?php

use Illuminate\Contracts\Auth\Authenticatable;

/**
 *  Return the current auth user.
 * 
 *  @return Illuminate\Contracts\Auth\Authenticatable
 */
if (!function_exists('current_user'))
{
    function current_user() : Authenticatable
    {
        return auth()->user();
    }
}