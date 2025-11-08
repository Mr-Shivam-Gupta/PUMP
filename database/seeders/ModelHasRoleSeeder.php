<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRoleSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            ['role_id' => 1, 'model_type' => 'App\\Models\\SuperAdmin', 'model_id' => 1],
        ];

        foreach ($records as $record) {
            DB::table('model_has_roles')->updateOrInsert(
                ['role_id' => $record['role_id'], 'model_id' => $record['model_id']],
                $record
            );
        }
    }
}
