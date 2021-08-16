<?php

namespace Tests\Trait;

use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

trait DriversLicenseTrait
{
    /**
     *  Valid drivers license form data
     * 
     *  @param array $params
     *  @return array
     */
    public function validLicenseData(array $params = []) : array
    {
        $image = UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(200);
        $dateIssued = Carbon::now()->subYears(2)->toDateString();
        $expirationDate = Carbon::now()->addYears(2)->toDateString();
        $birthdate = Carbon::now()->subYears(20)->toDateString();

        return [
            'license_image' => $params['license_image'] ?? $image,
            'license_number' => $params['license_number'] ?? 'az-483-12883-231',
            'state' => $params['state'] ?? $this->getRandomState(),
            'date_issued' => $params['date_issued'] ?? $dateIssued,
            'expiration_date' => $params['expiration_date'] ?? $expirationDate,
            'birthdate' => $params['birthdate'] ?? $birthdate,
            'street' => $params['street'] ?? '123 Fake St',
            'zip' => $params['zip'] ?? '54353',
            'city' => $params['city'] ?? 'Faketown'
        ];
    }

    /**
     *  Get a random valid license state
     * 
     *  @return string
     */
    public function getRandomState() : string
    {
        $states = [ 
            'Alabama', 'Alaska', 'American Samoa', 'Arizona', 'Arkansas', 'California', 'Colorado', 
            'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Guam', 'Hawaii', 'Idaho', 
            'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 
            'Michigan', 'Minnesota', 'Minor Outlying Islands', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 
            'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 
            'Northern Mariana Islands', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Puerto Rico', 'Rhode Island', 
            'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'U.S. Virgin Islands', 'Utah', 'Vermont', 
            'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
        ];

        return $states[array_rand($states)];
    }
}