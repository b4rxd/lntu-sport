<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Таблиця locations
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('enabled')->default(true);
            $table->string('title');
            $table->longText('description');
            $table->timestamps();
        });

        // Таблиця regular_schedulers
        Schema::create('regular_schedulers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('location_id')->nullable();
            $table->dateTime('date_from');
            $table->dateTime('date_till')->nullable();
            $table->unsignedSmallInteger('day_number');
            $table->time('time_from');
            $table->time('time_till');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });

        // Таблиця special_schedulers
        Schema::create('special_schedulers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('location_id')->nullable();
            $table->dateTime('date_from');
            $table->dateTime('date_till')->nullable();
            $table->dateTime('time_from');
            $table->dateTime('time_till');
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });

        // Таблиця vacation_schedulers
        Schema::create('vacation_schedulers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('location_id')->nullable();
            $table->string('title');
            $table->dateTime('date_from');
            $table->dateTime('date_till')->nullable();
            $table->unsignedSmallInteger('day_number');
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacation_schedulers');
        Schema::dropIfExists('special_schedulers');
        Schema::dropIfExists('regular_schedulers');
        Schema::dropIfExists('locations');
    }
};
