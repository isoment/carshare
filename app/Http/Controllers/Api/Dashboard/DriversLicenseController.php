<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriversLicenseRequest;
use App\Models\DriversLicense;
use App\Rules\States;
use App\Services\DriversLicenseService;
use Carbon\Carbon;
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
     *  Update a users drivers license
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function update(Request $request)
    {

    }

    /**
     *  Show a users license
     */
    public function show()
    {
        
    }
}
