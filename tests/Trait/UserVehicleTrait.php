<?php

namespace Tests\Trait;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;

trait UserVehicleTrait
{
    /**
     *  Valid form data for creating a new vehicle
     * 
     *  @param array $paramas
     * 
     *  @return array
     */
    public function validNewVehicleData(array $params = []) : array
    {
        $make = $this->getMake();

        return [
            'images' => $params['images'] ?? [$this->createFakeFile()],
            'featured_id' => $params['featured_id'] ?? 'test-file',
            'make' => $params['make'] ?? $make,
            'model' => $params['model'] ?? $this->getModel($make),
            'year' => $params['year'] ?? Carbon::now()->format('Y'),
            'plate' => $params['plate'] ?? 'XYZ-1234',
            'seats' => $params['seats'] ?? '4',
            'doors' => $params['doors'] ?? '4',
            'price' => $params['price'] ?? mt_rand(25, 400),
            'description' => $params['description'] ?? 'This is just a generic description for a vehicle',
        ];
    }

    /**
     *  Get a vehicle make
     * 
     *  @return string
     */
    public function getMake() : string
    {
        $make = VehicleMake::inRandomOrder()->first();

        return $make->make;
    }

    /**
     *  Get a vehicle model from a make
     * 
     *  @param string $make
     * 
     *  @return string
     */
    public function getModel(string $make) : string
    {
        $makeId = VehicleMake::where('make', $make)->first()->id;

        return VehicleModel::where('vehicle_make_id', $makeId)
            ->inRandomOrder()
            ->first()
            ->model;
    }

    /**
     *  Create a fake file
     * 
     *  @return Illuminate\Http\Testing\File
     */
    public function createFakeFile() : File
    {
        return UploadedFile::fake()->image('test-file.jpg', 1200, 800)->size(1000);
    }
}