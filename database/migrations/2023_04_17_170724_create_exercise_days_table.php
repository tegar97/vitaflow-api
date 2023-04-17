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
        Schema::create('exercise_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_type_id');
            $table->unsignedBigInteger('workout_day_id');
            $table->integer('exercise_order');
            $table->timestamps();

            $table->foreign('exercise_type_id')->references('id')->on('exercise_types');
            $table->foreign('workout_day_id')->references('id')->on('workout_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_days');
    }
};
