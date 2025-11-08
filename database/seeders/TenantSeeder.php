<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tenants')->insert([
            [
                'name' => 'Demo Tenant',
                'slug' => 'demo-tenant',
                'domain' => 'demo.yourdomain.com',
                'contact' => '9999999999',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'type' => 'subscription',
                'plan' => 'Starter Plan',
                'isolation' => 'shared_schema',
                'database' => null,
                'db_username' => null,
                'db_password' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tenant 2',
                'slug' => 'tenant 2',
                'domain' => 'tenant.yourdomain.com',
                'contact' => '9999999998',
                'email' => 'tenant@gmail.com',
                'password' => Hash::make('12345678'),
                'type' => 'subscription',
                'plan' => 'Starter Plan',
                'isolation' => 'shared_schema',
                'database' => null,
                'db_username' => null,
                'db_password' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
