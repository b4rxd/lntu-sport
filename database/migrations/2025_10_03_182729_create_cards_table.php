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
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('enabled')->default(true);
            $table->integer('count_usage')->default(0);
            $table->dateTime('valid_from');
            $table->dateTime('valid_till');
            $table->dateTime('last_usage')->nullable();
            $table->uuid('created_by_id');
            $table->uuid('price_id');
            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
