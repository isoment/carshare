<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     *  Clear any test entries in the cache leftover from
     *  previous tests
     * 
     *  @param array an array of keys to remove from the cache
     *  @return void
     */
    public function clearCache(array $keys = ['test-key']) : void
    {
        foreach ($keys as $key) {
            Cache::store('redis')->forget($key);
        }
        
    }
}
