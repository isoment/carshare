<?php

namespace Database\Seeders;

use App\Models\VehicleMake;
use Illuminate\Database\Seeder;

class VehicleMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
            'make' => 'Honda',
            'image' => '/img/vehicle-makes/honda.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Jeep',
            'image' => '/img/vehicle-makes/jeep.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Lexus',
            'image' => '/img/vehicle-makes/lexus.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Mercedes',
            'image' => '/img/vehicle-makes/mercedes.jpg'
        ]);

        VehicleMake::create([
            'make' => 'Porsche',
            'image' => '/img/vehicle-makes/porsche.jpg'
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

        VehicleMake::create([
            'make' => 'Volkswagen',
            'image' => '/img/vehicle-makes/volkswagen.jpg'
        ]);
    }
}
