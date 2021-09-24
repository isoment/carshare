<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutStoreRequest;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function store(CheckoutStoreRequest $request) 
    {
        return $this->checkoutService->store($request);
    }
}
