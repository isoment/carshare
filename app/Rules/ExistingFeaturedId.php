<?php

namespace App\Rules;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ExistingFeaturedId implements Rule
{
    private array|null $images;
    private int|null $vehicleId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array|null $images, int|null $vehicleId)
    {
        $this->images = $images;
        $this->vehicleId = $vehicleId;
    }

    /**
     * Check if the featured it passed in is either an existing image path or a new image name
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value === null) {
            return true;
        } else {
            // Get the existing vehicle image paths
            $existingVehicleImages = Vehicle::find($this->vehicleId)->vehicleImages
                ->pluck('image')
                ->toArray();

            $newImages = [];

            // Loop over the new images and add to array
            if ($this->images) {
                foreach ($this->images as $image) {
                    array_push($newImages, pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
                }
            }

            // Merge the two arrays
            $merged = array_merge($existingVehicleImages, $newImages);

            // Check if the value being validated is in the array
            return in_array($value, $merged);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid featured image';
    }
}
