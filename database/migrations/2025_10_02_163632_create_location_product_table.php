<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_products', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->uuid('location_id');

            $table->primary(['product_id', 'location_id']);

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_products');
    }
};