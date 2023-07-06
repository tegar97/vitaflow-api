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
        Schema::create('my_missions', function (Blueprint $table) {
            $table->id();
            // mission
            $table->foreignId('mission_id')->constrained('missions')->onDelete('cascade');
            // user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // status : finish , on-going
            $table->enum('status', ['on-going', 'finish'])->default('on-going');
            // target
            $table->integer('target');
            // current
            $table->integer('current');






            // type target : cal,langkah ,gelas
            $table->enum('type_target', ['cal', 'langkah', 'gelas','kg','bpm'])->default('cal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_missions');
    }
};
