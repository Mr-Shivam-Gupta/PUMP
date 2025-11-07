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
        Schema::table('permissions', function (Blueprint $table) {
            // Add new columns
            $table->unsignedBigInteger('permission_group_id')->nullable()->after('id');
            $table->string('permission_key')->nullable()->after('permission_group_id');
            $table->tinyInteger('status')->default(1)->after('permission_key');
            $table->unsignedBigInteger('created_by')->nullable()->after('created_at');
            $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');

            // Optional foreign keys
            // (uncomment if related tables exist)
            $table->foreign('permission_group_id')->references('id')->on('permission_groups')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['permission_group_id', 'permission_key', 'status', 'created_by', 'updated_by']);

            // Drop foreign keys if you added them
            $table->dropForeign(['permission_group_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
    }
};
