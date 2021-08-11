<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriversLicenseRequest;
use App\Http\Resources\ShowLicenseResource;
use App\Models\DriversLicense;
use App\Services\DriversLicenseService;
use Illuminate\Http\Request;

class DriversLicenseController extends Controller
{
    protected $driversLicenseService;

    public function __construct(DriversLicenseService $driversLicenseService)
    {
        $this->driversLicenseService = $driversLicenseService;
    }

    /**
     *  Create a new drivers license
     *  
     *  @param App\Http\Requests\DriversLicenseRequest $request
     */
    public function create(DriversLicenseRequest $request)
    {
        return $this->driversLicenseService->createDriversLicense($request);
    }
    
    /**
     *  Show a users license
     */
    public function show()
    {
        return new ShowLicenseResource(DriversLicense::showLicense());
    }
}
