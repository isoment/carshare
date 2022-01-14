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

/**
 *  Dollar formatted string to cents
 */
if (!function_exists('dollar_format_to_cents'))
{
    function dollar_format_to_cents(string $value)
    {
        return str_replace('.', '', $value);
    }
}