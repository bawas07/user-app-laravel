<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Generate random number of orders (0-10) for each user
            $numberOfOrders = rand(0, 10);
            
            for ($i = 0; $i < $numberOfOrders; $i++) {
                Order::create([
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
