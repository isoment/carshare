<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImages;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     *  Get a year for a vehicle
     */
    public function vehicleYear()
    {
        $currentYear = Carbon::now()->year;

        $startYear = Carbon::now()->subYears(10)->year;

        return rand($startYear, $currentYear);
    }

    /**
     *  Generate a plate number
     */
    public function plateNumber()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $letterPrefix = substr(str_shuffle($chars), 0, 2);

        return $letterPrefix . rand(11111, 999999);
    }

    private function imagePicker($make)
    {
        $chevy = [
            'chevy-309ru309f3qf.jpg',
            'chevy-98731hyrfwho.jpeg',
            'chevy-fafdsfsdf.jpg',
            'chevy-hdqefd839.jpg',
            'chevy-we8f30r.jpg'
        ];

        $ford = [
            'ford-32ur9832rf.jpg',
            'ford-3hyegfewuf.jpg',  
            'ford-3yh98qefhe.jpg',  
            'ford-eqgrewiugfh.jpg',  
            'ford-hf328ufe.jpg',
        ];

        $honda = [
            'honda-12jri3fs.jpg',  
            'honda-3219ur1hfo9eq.jpg',  
            'honda-3r983f3o.jpg',  
            'honda-feufewfe.jpeg',  
            'honda-feufhew.jpg',
        ];

        $jeep = [
            'jeep-213yr97813yfhue.jpg',  
            'jeep-31hfuqf.jpg',  
            'jeep-86r75u.jpg',  
            'jeep-dhiuqdhiq.jpg', 
            'jeep-fhouq38r.jpg',
        ];

        $lexus = [
            'lexus-31r32.jpg',  
            'lexus-fqehfiue.jpg',  
            'lexus-r3ihfu3w.jpg',  
            'lexus-wqy9fdw.jpg',
        ];

        $mercedes = [
            'mercedes-132r987qfyhewq.jpg',  
            'mercedes-32732798.webp',  
            'mercedes-327tdsai.jpg',  
            'mercedes-3hf8e93yq.jpg',  
            'mercedes-hf133fq.jpg'
        ];

        $porsche = [
            'porsche-13h738fdsfa.jpg',  
            'porsche-13rhy973fe.jpg',  
            'porsche-23r832hue.webp',  
            'porsche-318fhyef.jpg',  
            'porsche-3r8u9efew.jpg'
        ];

        $subaru = [
            'subaru-13hrf8ewf.webp',  
            'subaru-13ru98few.jpg',  
            'subaru-318f9ewf98ew.jpg',  
            'subaru-3fueqfhew.jpg'
        ];

        $tesla = [
            'tesla-132yrh87.jpg',  
            'tesla-3y938fr32.jpg',  
            'tesla-9873hyr9ef.jpg',  
            'tesla-r3yh98fewf.jpeg'
        ];

        $toyota = [
            'toyota-12987dyhdwq.jpg',  
            'toyota-21ydw98d.jpg',  
            'toyota-dh3iry3.webp'
        ];

        $volkswagen =[
            'volkswagen-398rhyq8dj.jpg',  
            'volkswagen-398yhwe98ufe.jpg',  
            'volkswagen-3hyr9eqhfea.jpg',  
            'volkswagen-hufhf9f8uhe.jpg',  
            'volkswagen-r98y329ef.jpg',
        ];

        if ($make === 'Chevy') {
            return $chevy[array_rand($chevy)];
        }

        if ($make === 'Ford') {
            return $ford[array_rand($ford)];
        }

        if ($make === 'Honda') {
            return $honda[array_rand($honda)];
        }

        if ($make === 'Jeep') {
            return $jeep[array_rand($jeep)];
        }

        if ($make === 'Lexus') {
            return $lexus[array_rand($lexus)];
        }

        if ($make === 'Mercedes') {
            return $mercedes[array_rand($mercedes)];
        }

        if ($make === 'Porsche') {
            return $porsche[array_rand($porsche)];
        }

        if ($make === 'Subaru') {
            return $subaru[array_rand($subaru)];
        }

        if ($make === 'Tesla') {
            return $tesla[array_rand($tesla)];
        }

        if ($make === 'Toyota') {
            return $toyota[array_rand($toyota)];
        }

        if ($make === 'Volkswagen') {
            return $volkswagen[array_rand($volkswagen)];
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
            'user_id' => User::all()->random()->id,
            'vehicle_model_id' => VehicleModel::all()->random()->id,
            'year' => $this->vehicleYear(),
            'plate_num' => $this->plateNumber(),
            'price_day' => $this->faker->randomFloat(0, 35, 500),
            'description' => $this->faker->paragraph(4),
            'doors' => $this->faker->numberBetween(2, 4),
            'seats' => $this->faker->numberBetween(2, 4)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Vehicle $vehicle) {
            for ($i = 0; $i < 5; $i++) {
                VehicleImages::create([
                    'vehicle_id' => $vehicle->id,
                    'image' => 'storage/vehicle-seeder-img/' . 
                        $this->imagePicker($vehicle->vehicleModel->vehicleMake->make)
                ]);
            }
        });
    }
}
