<?php

namespace Database\Factories;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleModel::class;

    public function listVehicleModelsByType(string $vehicleMake)
    {
        if ($vehicleMake === 'Chevy') {
            return [
                'Camaro', 'Corvette', 'Colorado', 'Impala', 'Sonic', 'Malibu', 'Express',
                'Suburban', 'Blazer', 'Tahoe', 'Trax', 'Traverse', 'Colorado', 'Silverado 1500'
            ];
        }

        if ($vehicleMake === 'Ford') {
            return [
                'Fusion', 'Mustang', 'Focus', 'Bronco', 'Edge', 'Escape', 'Expedition', 'Explorer',
                'F-150', 'F-250', 'Ranger', 'Transit'
            ];
        }

        if ($vehicleMake === 'Honda') {
            return [
                'Accord', 'Civic', 'Clarity', 'Fit', 'Insight', 'CR-V', 'CR-V Hybrid', 'HR-V', 'Passport',
                'Pilot', 'Ridgeline'
            ];
        }

        if ($vehicleMake === 'Jeep') {
            return [
                'Cherokee', 'Compass', 'Grand Cherokee', 'Renegade', 'Wrangler', 'Gladiator'
            ];
        }

        if ($vehicleMake === 'Lexus') {
            return [
                'ES 250', 'ES 300h', 'ES 350', 'GS 350', 'IS 300', 'IS 350', 'LC 500', 'LS 500',
                'RC 300', 'RC 350', 'RC F', 'GX 460', 'LX 570', 'RX 350', 'UX 200'
            ];
        }

        if ($vehicleMake === 'Mercedes') {
            return [
                'AMG A 35', 'A-Class', 'AMG E 53', 'AMG C 63', 'AMG GT 43', 'AMG GT', 'C-Class',
                'E-Class', 'S-Class', 'SL 550', 'GLA 35', 'GLB 35', 'GLE 63', 'GLA 250', 'GLS 580'
            ];
        }

        if ($vehicleMake === 'Porsche') {
            return [
                '911', 'Taycan', 'Panamera', 'Cayman', 'Spyder', 'Boxster', 'Cayenne', 'Macan'
            ];
        }

        if ($vehicleMake === 'Subaru') {
            return [
                'Impreza', 'Legacy', 'WRX', 'Crosstrek', 'Ascent', 'Outback', 'Forester'
            ];
        }

        if ($vehicleMake === 'Tesla') {
            return [
                'Model 3', 'Model S', 'Model X', 'Model Y'
            ];
        }

        if ($vehicleMake === 'Toyota') {
            return [
                'Avalon', 'Camry', 'Corolla', 'Prius', 'Supra', 'Yaris', '4-Runner', 'Highlander',
                'Land Cruiser', 'RAV4', 'Sequoia', 'Tacoma', 'Tundra', 'Sienna'
            ];
        }

        if ($vehicleMake === 'Volkswagen') {
            return [
                'Golf', 'Jetta', 'Passat', 'Atlas', 'Taos', 'Tiguan'
            ];
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vehicle_make_id' => VehicleMake::all()->random()->id,
            'model' => 'Default'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(VehicleModel $vehicleModel) {

            $modelArray = $this->listVehicleModelsByType($vehicleModel->vehicleMake->make);

            $vehicleModel->update([
                'model' => $modelArray[array_rand($modelArray, 1)]
            ]);

        });
    }
}
