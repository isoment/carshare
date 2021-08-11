<?php

namespace App\Services;

use App\Models\DriversLicense;
use Illuminate\Support\Facades\Storage;

class DriversLicenseService
{
    /**
     *  Create a new drivers license entry for the user
     */
    public function createDriversLicense($request)
    {
        $user = auth()->user();

        // Check if the user has a license on record
        if ($user->driversLicense) {
            $this->licenseImageCleanup($user->driversLicense->license_image);
        }

        $image = $request->license_image;

        $newName = time() . sha1(random_bytes(10)) . auth()->id() . sha1(random_bytes(8)) . '.' . $image->extension();

        $image->storeAs('license-images', $newName, 'public');

        $fullPath = '/storage/license-images/' . $newName;

        DriversLicense::updateOrCreate(
            ['user_id' => $user->id],
            [
                'number' => $request->license_number,
                'state' => $request->state,
                'issued' => $request->date_issued,
                'expiration' => $request->expiration_date,
                'dob' => $request->birthdate,
                'street' => $request->street,
                'city' => $request->city,
                'zip' => $request->zip,
                'license_image' => $fullPath,
            ]
        );

        return response()->json('Drivers license submitted', 201);
    }

    /**
     *  Delete the old uploaded image of the users license if it exists
     * 
     *  @param string $imagePath
     */
    private function licenseImageCleanup($imagePath)
    {
        $fileName = explode("/", $imagePath);

        $file = $fileName[2] . '/' . $fileName[3];

        Storage::disk('public')->delete($file);
    }
}