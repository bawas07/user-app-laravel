<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 3 active users
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => $faker->name() . ' (seed)',
                'email' => $faker->unique()->safeEmail(),
                'password' => 'password123',
                'active' => true
            ]);
        }

        // Create 2 inactive users
        for ($i = 1; $i <= 2; $i++) {
            User::create([
                'name' => $faker->name() . ' (seed)',
                'email' => $faker->unique()->safeEmail(),
                'password' => 'password123',
                'active' => false
            ]);
        }
    }
}
