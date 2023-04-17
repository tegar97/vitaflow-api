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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('food_name');
            $table->string('food_image');
            $table->string('food_rating');
            $table->decimal('food_calories', 10, 2)->nullable();
            $table->decimal('food_fat', 10, 2)->nullable();
            $table->decimal('food_saturated_fat', 10, 2)->nullable();;
            $table->decimal('food_trans_fat', 10, 2)->nullable();
            $table->decimal('food_cholesterol', 10, 2)->nullable();
            $table->decimal('food_sodium', 10, 2)->nullable();
            $table->decimal('food_carbohydrate', 10, 2)->nullable();
            $table->decimal('food_fiber', 10, 2)->nullable();
            $table->decimal('food_sugar', 10, 2)->nullable();
            $table->decimal('food_protein', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
