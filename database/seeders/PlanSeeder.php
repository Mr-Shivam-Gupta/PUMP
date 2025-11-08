<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('plans')->insert([
            [
                'name' => 'Starter Plan',
                'duration_days' => 30,
                'isolation' => 'shared_schema',
                'max_users' => 10,
                'price' => 499.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro Plan',
                'duration_days' => 90,
                'isolation' => 'shared_schema',
                'max_users' => 50,
                'price' => 1999.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise Plan',
                'duration_days' => 365,
                'isolation' => 'shared_schema',
                'max_users' => 500,
                'price' => 9999.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
