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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();

            // mission
            $table->string('name');
            $table->string('mission_code');
            $table->string('alternatif_name');
            $table->string('description');
            $table->string('icon');
            $table->string('color_Theme');
            $table->integer('order_number');
            // point
            $table->integer('point')->default(0);
            // coin
            $table->integer('coin')->default(0);
            // bamboo]
            $table->integer('bamboo')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
