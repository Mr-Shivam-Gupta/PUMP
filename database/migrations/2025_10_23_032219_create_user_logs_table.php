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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0)->index();
            $table->string('ip_address');
            $table->boolean('is_success')->default(0);
            $table->text('user_agent');
            $table->string('timestamp'); // Can also be datetime if you prefer
            $table->text('status');
            $table->string('login_method')->nullable();
            $table->string('device_type')->nullable();
            $table->text('device_details')->nullable();
            $table->timestamps(); // created_at & updated_at
        });

        // Optional: Add foreign key if you have users table
        // Schema::table('user_logs', function (Blueprint $table) {
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
