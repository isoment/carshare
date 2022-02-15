<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class UserStatisticsController extends Controller
{
    public StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function renterStats()
    {
        return $this->statisticsService->showRenterStats();
    }

    public function hostStats()
    {
        return $this->statisticsService->showHostStats();
    }
}
