<?php

use App\Utils\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id');
            $table->uuid('doctor_id');
            $table->uuid('schedule_id');
            $table->uuid('medical_specialty_id')->nullable();
            $table->enum('status', [
                Constants::$PENDING,
                Constants::$APPROVED,
                Constants::$REJECTED,
            ])->default(Constants::$PENDING);
            $table->string('healthcare_provider')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->text('file')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('medical_specialty_id')->references('id')->on('medical_specialties');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
