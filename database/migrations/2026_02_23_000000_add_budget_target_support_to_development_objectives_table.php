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
            $table->decimal('budget_requirement', 10, 2)->nullable()->after('action_plan');
            $table->string('target_period')->nullable()->after('budget_requirement');
            $table->text('support_required')->nullable()->after('target_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('development_objectives', function (Blueprint $table) {
            $table->dropColumn(['budget_requirement', 'target_period', 'support_required']);
        });
    }
};
