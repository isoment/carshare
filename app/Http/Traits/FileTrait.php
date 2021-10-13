<?php

namespace App\Http\Traits;

trait FileTrait
{
    /**
     *  Method to give the file a unique name
     * 
     *  @param string $extension
     * 
     *  @return string
     */
    public function generateFileName(string $extension) : string
    {
        return time() . sha1(random_bytes(10)) . auth()->id() . sha1(random_bytes(8)) . '.' . $extension;
    }
}