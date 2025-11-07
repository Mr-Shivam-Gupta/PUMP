<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration_days')->default(30);
            $table->enum('isolation', ['shared_schema', 'separate_db'])->default('shared_schema');
            $table->integer('max_users')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_trial')->default(false);
            $table->tinyInteger('subscription_status')->default(1)->comment('1 = active, 0 = expired, 2 = trial, 3 = canceled');
            $table->timestamps();
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('shift_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->timestamps();
        });

        Schema::create('fuel_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('fuel_name');
            $table->string('unit_of_measure');
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->timestamps();
        });

        Schema::create('pumps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('pump_name')->comment('pump name or number');
            $table->string('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->timestamps();
        });

        Schema::create('pump_nozzles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('pump_id')->constrained('pumps')->cascadeOnDelete();
            $table->foreignId('fuel_type_id')->constrained('fuel_types')->cascadeOnDelete();
            $table->string('nozzle_name')->comment('nozzle name or number');
            $table->string('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->timestamps();
        });

        Schema::create('payment_modes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('payment_mode')->comment('payment mode name');
            $table->string('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->timestamps();
        });

        Schema::create('daily_fuel_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('fuel_type_id')->constrained('fuel_types')->cascadeOnDelete();
            $table->decimal('price_per_unit', 10, 2);
            $table->date('effective_date');
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('manager_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->restrictOnDelete();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->restrictOnDelete();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->date('assignment_date');
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pump_id')->constrained('pumps')->cascadeOnDelete();
            $table->unsignedBigInteger('pump_nozzle_ids');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('nozzle_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->restrictOnDelete();
            $table->foreignId('pump_nozzle_id')->constrained('pump_nozzles')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('opening_reading', 15, 2);
            $table->decimal('closing_reading', 15, 2)->nullable();
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('sold_quantity', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->dateTime('opened_at');
            $table->foreignId('closed_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('advance_currency_on_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->restrictOnDelete();
            $table->foreignId('taken_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('given_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('advance_amount', 15, 2);
            $table->json('denominations')->nullable()->comment('currency denominations eg. {100rs:5, 50rs:10}');
            $table->timestamps();
        });

        Schema::create('payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->restrictOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->decimal('total_sold_amount', 15, 2);
            $table->decimal('total_advance_amount', 15, 2);
            $table->decimal('total_submission_amount', 15, 2);
            $table->json('denominations')->nullable()->comment('currency denominations eg. {100rs:5, 50rs:10}');
            $table->tinyInteger('status')->default(0)->comment('0 = pending, 1 = verified, 2 = rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
