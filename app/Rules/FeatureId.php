<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FeatureId implements Rule
{
    private array|null $images;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array|null $images)
    {
        $this->images = $images;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->images === null) {
            return false;
        }

        $fileNames = [];

        foreach ($this->images as $image) {
            array_push($fileNames, pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
        }

        return in_array($value, $fileNames);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid image id';
    }
}
