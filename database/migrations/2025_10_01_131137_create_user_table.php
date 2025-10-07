<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('roles')->nullable();
            $table->json('access_list')->nullable();
            $table->json('location_access_list')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
