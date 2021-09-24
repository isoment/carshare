<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutStoreRequest;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    protected $checkoutService;

    /**
     *  @param App\Services\CheckoutService $checkoutService
     */
    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     *  Checkout and store the order information
     * 
     *  @param CheckoutStoreRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function store(CheckoutStoreRequest $request) : JsonResponse
    {
        return $this->checkoutService->store($request);
    }
}
