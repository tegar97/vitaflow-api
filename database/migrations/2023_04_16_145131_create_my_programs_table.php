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
        Schema::create('my_programs', function (Blueprint $table) {
            $table->id();
            // relationship to users table and programs table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            // status
            $table->enum('status', ['on-going', 'finish','cancel'])->default('on-going');
            // start date and end date
            $table->date('join_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_programs');
    }
};
