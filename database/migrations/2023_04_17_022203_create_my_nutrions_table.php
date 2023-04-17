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
        Schema::create('my_nutrions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('program_id')->constrained('programs');
            $table->date('date');
            $table->integer('targetCalories');
            $table->integer('calorieLeft');
            $table->integer('activityCalories');
            $table->integer('carbohydrate');
            $table->integer('protein');
            $table->integer('fat');
            $table->integer('intakeCalories');
            $table->double('akg');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_nutrions');
    }
};
