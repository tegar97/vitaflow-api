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
        Schema::create('food_activity_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('food_id');
            $table->foreign('food_id')->references('id')->on('food')->onDelete('cascade');
            $table->unsignedBigInteger('my_mission_id');
            $table->foreign('my_mission_id')->references('id')->on('my_missions')->onDelete('cascade');
            $table->integer('calorie_intake');
            $table->integer('carbohydrate_intake');
            $table->integer('protein_intake');
            $table->integer('fat_intake');
            $table->integer('size');
            $table->String('unit');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);



            $table->date('date');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_activity_trackings');
    }
};
