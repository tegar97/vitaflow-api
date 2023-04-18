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
        Schema::create('workout_days', function (Blueprint $table) {
            $table->id();
            $table->integer('day_number');
            $table->string('workout_name');
            $table->integer('workout_duration');
            $table->integer('workout_intensity');

            $table->unsignedBigInteger('program_id');
            // image character
            $table->string('workout_image');
            // background
            $table->string('workout_background');
            $table->string('workout_description');
            $table->foreign('program_id')->references('id')->on('exercise_programs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_days');
    }
};
