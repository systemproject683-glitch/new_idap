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
        Schema::create('conducted_interventions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type_of_lnd');
            $table->string('title');
            $table->date('date_conducted')->nullable();
            $table->string('duration')->nullable();
            $table->string('leaving_service_provided')->nullable();
            $table->integer('target_number_of_participants')->nullable();
            $table->integer('actual_number_of_participants')->nullable();
            $table->integer('completion_rate')->nullable();
            $table->string('proof_of_documentation')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conducted_interventions');
    }
};
