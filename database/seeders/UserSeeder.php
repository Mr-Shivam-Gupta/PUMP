<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'tenant_id' => 1,
                'employee_id' => 'EMP001',
                'name' => 'Tenant Admin',
                'phone' => '8888888888',
                'email' => 'admin@tenant.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
