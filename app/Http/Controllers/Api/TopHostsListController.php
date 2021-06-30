<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TopHostsListResource;
use App\Models\User;
use App\Services\TopHostsListService;

class TopHostsListController extends Controller
{
    protected $topHostsListService;

    public function __construct(TopHostsListService $topHostsListService)
    {
        $this->topHostsListService = $topHostsListService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $topHostsList = $this->topHostsListService->index();

        return TopHostsListResource::collection(
            $topHostsList
        );
    }
}
