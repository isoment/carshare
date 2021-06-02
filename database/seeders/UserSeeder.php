<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some hosts
        User::factory()->count(100)->create([
            'host' => 1
        ]);

        // Create some top hosts
        User::factory()->count(22)->create([
            'host' => 1,
            'top_host' => 1
        ]);

        // Create some non hosts
        User::factory()->count(300)->create([
            'host' => 0
        ]);
    }
}
