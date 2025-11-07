<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            // $table->string('id')->primary();
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable();

            $table->string('contact')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('license_number')->nullable();

            $table->enum('type', ['subscription', 'direct']);
            $table->string('plan')->nullable();
            $table->enum('isolation', ['shared_schema', 'separate_schema', 'separate_db']);
            $table->string('database')->nullable();
            $table->string('db_username')->nullable();
            $table->string('db_password')->nullable();

            $table->timestamps();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
