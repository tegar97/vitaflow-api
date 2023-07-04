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
        Schema::create('daily_logins', function (Blueprint $table) {
            $table->id();
            $table->date('login_date');
            $table->boolean('reward_received')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('reward_image')->nullable();
            $table->string('reward_title');
            $table->integer('reward_value');
            $table->enum('reward_type', ['type_coin', 'type_bamboo', 'type_vip_subscription']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logins');
    }
};
