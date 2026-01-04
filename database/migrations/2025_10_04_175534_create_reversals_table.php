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
            $table->uuid('subscription_id')->nullable();;
            $table->uuid('created_by_id')->nullable();;
            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
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
