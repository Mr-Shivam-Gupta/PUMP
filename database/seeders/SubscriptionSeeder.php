<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all plans and tenants
        $plans = DB::table('plans')->get();
        $tenants = DB::table('tenants')->pluck('id')->toArray();

        $subscriptions = [];

        // Generate 1000+ subscriptions
        for ($i = 0; $i < 1000; $i++) {
            $tenantId = $tenants[array_rand($tenants)];
            $plan = $plans->random();

            // Randomly decide if trial or paid
            $isTrial = rand(0, 1) === 1;

            $startDate = Carbon::now()->subDays(rand(0, 60));
            $durationDays = $isTrial ? 14 : $plan->duration_days;
            $endDate = (clone $startDate)->addDays($durationDays);

            // Determine subscription status
            $today = Carbon::now();
            $status = $isTrial ? 2 : 1; // default trial or active
            if ($today->gt($endDate)) {
                $status = 0; // expired
            }

            $subscriptions[] = [
                'plan_id' => $plan->id,
                'tenant_id' => $tenantId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_trial' => $isTrial,
                'subscription_status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all at once for speed
        DB::table('subscriptions')->insert($subscriptions);
    }
}
