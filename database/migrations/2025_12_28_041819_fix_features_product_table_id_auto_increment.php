<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists
        if (Schema::hasTable('features_product')) {
            // Try to modify the id column to be auto increment
            // This will work if the column exists but is not auto increment
            try {
                DB::statement('ALTER TABLE `features_product` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
            } catch (\Exception $e) {
                // If that fails, try to drop and recreate
                // First, check if id column exists
                if (Schema::hasColumn('features_product', 'id')) {
                    // Drop primary key first if exists
                    try {
                        DB::statement('ALTER TABLE `features_product` DROP PRIMARY KEY');
                    } catch (\Exception $e) {
                        // Ignore if primary key doesn't exist
                    }
                    // Modify id column
                    DB::statement('ALTER TABLE `features_product` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)');
                } else {
                    // Add id column if it doesn't exist
                    Schema::table('features_product', function (Blueprint $table) {
                        $table->id()->first();
                    });
                }
            }
        } else {
            // If table doesn't exist, create it with proper structure
            Schema::create('features_product', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't reverse this fix as it's a database structure correction
    }
};
