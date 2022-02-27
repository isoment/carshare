<?php

namespace App\Http\Traits;

trait CacheModeTrait
{
    /**
     *  When we are running tests we want to be able to clear the cache
     *  after each test. If the database is SQLite use the key 'test-key'
     *  otherwise use the key that is passed in.
     * 
     *  @param $productionKey the key to use for production
     *  @param $tstKey the key for testing
     *  @return string
     */
    public function setCacheMode(string $productionKey, string $testKey = 'test-key') : string
    {
        if (env('DB_CONNECTION') === 'sqlite') {
            return $testKey;
        } else {
            return $productionKey;
        }
    }
}