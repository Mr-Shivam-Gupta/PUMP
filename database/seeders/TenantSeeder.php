<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('tenants')->insert([
        //     [
        //         'name' => 'Demo Tenant',
        //         'slug' => 'demo-tenant',
        //         'domain' => 'demo.yourdomain.com',
        //         'contact' => '9999999999',
        //         'email' => 'admin@gmail.com',
        //         'password' => Hash::make('12345678'),
        //         'type' => 'subscription',
        //         'plan' => 'Starter Plan',
        //         'isolation' => 'shared_schema',
        //         'database' => null,
        //         'db_username' => null,
        //         'db_password' => null,
        //         'status' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'name' => 'Tenant 2',
        //         'slug' => 'tenant 2',
        //         'domain' => 'tenant.yourdomain.com',
        //         'contact' => '9999999998',
        //         'email' => 'tenant@gmail.com',
        //         'password' => Hash::make('12345678'),
        //         'type' => 'subscription',
        //         'plan' => 'Starter Plan',
        //         'isolation' => 'shared_schema',
        //         'database' => null,
        //         'db_username' => null,
        //         'db_password' => null,
        //         'status' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ]);
        $tenants = [];

        // create 100+ tenants dynamically
        for ($i = 1; $i <= 120; $i++) {
            $slug = 'tenant-' . $i;
            $tenants[] = [
                'name' => 'Tenant ' . $i,
                'slug' => $slug,
                'domain' => $slug . '.yourdomain.com',
                'contact' => '9999999' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'email' => 'tenant' . $i . '@yourdomain.com',
                'password' => Hash::make('12345678'),
                'type' => 'subscription',
                'plan' => $this->getRandomPlan(),
                'isolation' => $this->getRandomIsolation(),
                'database' => null,
                'db_username' => null,
                'db_password' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tenants')->insert($tenants);
    }

    private function getRandomPlan(): string
    {
        $plans = ['Starter Plan', 'Business Plan', 'Enterprise Plan'];
        return $plans[array_rand($plans)];
    }

    private function getRandomIsolation(): string
    {
        $isolations = ['shared_schema', 'separate_db'];
        return $isolations[array_rand($isolations)];
    }
}
