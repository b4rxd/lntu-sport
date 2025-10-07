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
        Schema::create('reversals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('amount_in_uah');
            $table->uuid('card_id');
            $table->uuid('created_by_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reversals');
    }
};
