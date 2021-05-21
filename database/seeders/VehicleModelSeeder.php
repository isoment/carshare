<?php

namespace Database\Seeders;

use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // VehicleModel::factory()->count(50)->create();

        $chevyModels = [
            'Camaro', 'Corvette', 'Colorado', 'Impala', 'Sonic', 'Malibu', 'Express',
            'Suburban', 'Blazer', 'Tahoe', 'Trax', 'Traverse', 'Colorado', 'Silverado 1500'
        ];

        foreach ($chevyModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 1,
                'model' => $model
            ]);
        }

        $fordModels = [
            'Fusion', 'Mustang', 'Focus', 'Bronco', 'Edge', 'Escape', 'Expedition', 'Explorer',
            'F-150', 'F-250', 'Ranger', 'Transit'
        ];

        foreach ($fordModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 2,
                'model' => $model
            ]);
        }

        $hondaModels = [
            'Accord', 'Civic', 'Clarity', 'Fit', 'Insight', 'CR-V', 'CR-V Hybrid', 'HR-V', 'Passport',
            'Pilot', 'Ridgeline'
        ];

        foreach ($hondaModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 3,
                'model' => $model
            ]);
        }

        $jeepModels = [
            'Cherokee', 'Compass', 'Grand Cherokee', 'Renegade', 'Wrangler', 'Gladiator'
        ];

        foreach ($jeepModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 4,
                'model' => $model
            ]);
        }

        $lexusModels = [
            'ES 250', 'ES 300h', 'ES 350', 'GS 350', 'IS 300', 'IS 350', 'LC 500', 'LS 500',
            'RC 300', 'RC 350', 'RC F', 'GX 460', 'LX 570', 'RX 350', 'UX 200'
        ];

        foreach ($lexusModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 5,
                'model' => $model
            ]);
        }

        $mercedesModels = [
            'AMG A 35', 'A-Class', 'AMG E 53', 'AMG C 63', 'AMG GT 43', 'AMG GT', 'C-Class',
            'E-Class', 'S-Class', 'SL 550', 'GLA 35', 'GLB 35', 'GLE 63', 'GLA 250', 'GLS 580'
        ];

        foreach ($mercedesModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 6,
                'model' => $model
            ]);
        }

        $porscheModels = [
            '911', 'Taycan', 'Panamera', 'Cayman', 'Spyder', 'Boxster', 'Cayenne', 'Macan'
        ];

        foreach ($porscheModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 7,
                'model' => $model
            ]);
        }

        $subaruModels = [
            'Impreza', 'Legacy', 'WRX', 'Crosstrek', 'Ascent', 'Outback', 'Forester'
        ];

        foreach ($subaruModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 8,
                'model' => $model
            ]);
        }

        $teslaModels = [
            'Model 3', 'Model S', 'Model X', 'Model Y'
        ];

        foreach ($teslaModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 9,
                'model' => $model
            ]);
        }

        $toyotaModels = [
            'Avalon', 'Camry', 'Corolla', 'Prius', 'Supra', 'Yaris', '4-Runner', 'Highlander',
            'Land Cruiser', 'RAV4', 'Sequoia', 'Tacoma', 'Tundra', 'Sienna'
        ];

        foreach ($toyotaModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 10,
                'model' => $model
            ]);
        }

        $volkswagenModels = [
            'Golf', 'Jetta', 'Passat', 'Atlas', 'Taos', 'Tiguan'
        ];

        foreach ($volkswagenModels as $model) {
            VehicleModel::create([
                'vehicle_make_id' => 11,
                'model' => $model
            ]);
        }
    }
}
