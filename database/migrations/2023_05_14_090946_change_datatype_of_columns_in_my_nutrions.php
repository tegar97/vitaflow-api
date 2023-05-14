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
        Schema::table('my_nutrions', function (Blueprint $table) {
            $table->double('carbohydrate')->change();
            $table->double('protein')->change();
            $table->double('fat')->change();
            $table->double('intakeCalories')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_nutrions', function (Blueprint $table) {
            $table->integer('carbohydrate')->change();
            $table->integer('protein')->change();
            $table->integer('fat')->change();
            $table->integer('intakeCalories')->change();
        });
    }
};
