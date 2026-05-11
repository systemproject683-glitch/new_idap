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
            $table->string('lnd_type')->nullable()->after('title');
            $table->string('lnd_title')->nullable()->after('lnd_type');
            $table->string('lnd_period_date')->nullable()->after('lnd_title');
            $table->decimal('lnd_hours', 8, 2)->nullable()->after('lnd_period_date');
            $table->text('lnd_proof_completion')->nullable()->after('lnd_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('development_objectives', function (Blueprint $table) {
            $table->dropColumn(['lnd_type', 'lnd_title', 'lnd_period_date', 'lnd_hours', 'lnd_proof_completion']);
        });
    }
};
