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
        Schema::table('development_objectives', function (Blueprint $table) {
            $table->string('target_date_from')->nullable()->after('target_period');
            $table->string('target_date_to')->nullable()->after('target_date_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('development_objectives', function (Blueprint $table) {
            $table->dropColumn(['target_date_from', 'target_date_to']);
        });
    }
};
