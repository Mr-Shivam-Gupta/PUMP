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
                'name' => 'Tenant Supervisor',
                'phone' => '8888888888',
                'role' => 'Supervisor',
                'email' => 'supervisor@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'employee_id' => 'EMP002',
                'name' => 'Tenant Operator',
                'phone' => '8888888888',
                'role' => 'Operator',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
