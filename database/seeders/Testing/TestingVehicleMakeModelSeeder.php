<?php

namespace Database\Seeders\Testing;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class TestingVehicleMakeModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static function run()
    {
        VehicleMake::create([
            'make' => 'Chevy',
            'image' => '/img/vehicle-makes/chevy.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Ford',
            'image' => '/img/vehicle-makes/ford.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Subaru',
            'image' => '/img/vehicle-makes/subaru.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Tesla',
            'image' => '/img/vehicle-makes/tesla.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Toyota',
            'image' => '/img/vehicle-makes/toyota.jpg'
        ]);

        $chevyModels = [
            'Camaro', 'Corvette', 'Colorado', 'Express', 'Suburban', 'Tahoe', 'Colorado', 'Silverado 1500'
        ];

        foreach ($chevyModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 1,
                'model' => $model
            ]);
        }

        $fordModels = [
            'Fusion', 'Mustang', 'Focus', 'Expedition', 'Explorer', 'F-150', 'F-250', 'Ranger', 'Transit'
        ];

        foreach ($fordModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 2,
                'model' => $model
            ]);
        }

        $subaruModels = [
            'Impreza', 'Legacy', 'WRX', 'Crosstrek', 'Ascent', 'Outback', 'Forester'
        ];

        foreach ($subaruModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 3,
                'model' => $model
            ]);
        }

        $teslaModels = [
            'Model 3', 'Model S', 'Model X', 'Model Y'
        ];

        foreach ($teslaModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 4,
                'model' => $model
            ]);
        }

        $toyotaModels = [
            'Avalon', 'Camry', 'Corolla', 'Prius', 'Land Cruiser', 'RAV4', 'Sequoia', 'Tacoma', 'Sienna'
        ];

        foreach ($toyotaModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 5,
                'model' => $model
            ]);
        }
    }
}
