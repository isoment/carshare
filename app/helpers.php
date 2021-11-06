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

/**
 *  Remove 'storage' from file path
 * 
 *  @param string $path
 * 
 *  @return string
 */
if (!function_exists('remove_storage_file_path'))
{
    function remove_storage_file_path(string $path) : string
    {
        return explode("/", $path)[2] . "/" . explode("/", $path)[3];
    }
}