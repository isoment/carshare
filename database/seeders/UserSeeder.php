<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $renterCount number of renters to create
     * @param int $hostCount number of hosts to create
     * @param int $topHostCount number of top hosts to create
     * @return void
     */
    public static function run(int $renterCount = 300, int $hostCount = 100, int $topHostCount = 22)
    {
        // Create some renters
        User::factory()->count($renterCount)->create([
            'host' => 0
        ]);

        // Create some hosts
        User::factory()->count($hostCount)->create([
            'host' => 1
        ]);

        // Create some top hosts
        User::factory()->count($topHostCount)->create([
            'host' => 1,
            'top_host' => 1
        ]);
    }
}
