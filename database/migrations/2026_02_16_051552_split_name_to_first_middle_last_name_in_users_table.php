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
        Schema::table('users', function (Blueprint $table) {
            // Add new name fields
            $table->string('first_name')->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
        });

        // Migrate existing data
        DB::statement('UPDATE users SET 
            first_name = SUBSTRING_INDEX(name, " ", 1),
            last_name = SUBSTRING_INDEX(name, " ", -1),
            middle_name = CASE 
                WHEN LENGTH(name) - LENGTH(REPLACE(name, " ", "")) > 1 
                THEN TRIM(SUBSTRING(SUBSTRING_INDEX(name, " ", 2), LENGTH(SUBSTRING_INDEX(name, " ", 1)) + 1))
                ELSE NULL 
            END
            WHERE name IS NOT NULL');

        // Make the new fields required after data migration
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });

        // Drop the old name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the old name column
            $table->string('name')->after('id');
        });

        // Recombine the name fields
        DB::statement('UPDATE users SET 
            name = CONCAT(
                COALESCE(first_name, ""), 
                CASE WHEN middle_name IS NOT NULL AND middle_name != "" THEN CONCAT(" ", middle_name) ELSE "" END,
                " ", 
                COALESCE(last_name, "")
            )');

        // Drop the new name fields
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
        });
    }
};
