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
        Schema::table('users', function (Blueprint $table) {
            //age,gender,height,weight, bmi ,goal ,target_weight  : all these are added to the users table with opsional
            $table->integer('age')->nullable();
            $table->enum('gender', ['laki-laki','perempuan'])->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->double('bmi')->nullable();
            $table->enum('goal', ['gain', 'loss', 'maintain'])->nullable();
            $table->integer('target_weight')->nullable();
            





        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
