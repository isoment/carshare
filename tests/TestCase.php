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
     *  @return void
     */
    public function clearCache() : void
    {
        Cache::store('redis')->forget('test-key');
    }
}
