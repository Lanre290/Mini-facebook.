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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->longText('text');
            $table->integer('sender');
            $table->integer('receiver');
            $table->string('status')->default('normal');
            $table->timestamp('timestamp');
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->string('hour');
            $table->string('minute');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
