<?php

use App\Utils\Constants;
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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', [
                Constants::$MALE,
                Constants::$FEMALE
            ])->default(Constants::$MALE);
            $table->string('address')->nullable();
            $table->enum('status', [
                Constants::$ACTIVE,
                Constants::$INACTIVE
            ])->default(Constants::$ACTIVE);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
