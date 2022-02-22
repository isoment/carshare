<?php

namespace App\Http\Traits;

trait CacheModeTrait
{
    /**
     *  When we are running tests we want to be able to clear the cache
     *  after each test. If the database is SQLite use the key 'test-key'
     *  otherwise use the key that is passed in.
     * 
     *  @param $keyName
     *  @return string
     */
    public function setCacheMode(string $keyName) : string
    {
        return env('DB_CONNECTION') === 'sqlite' ? 
            'test-key' : 
            $keyName;
    }
}