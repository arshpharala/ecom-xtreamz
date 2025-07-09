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
        Schema::create('product_countries', function (Blueprint $table) {
            $table->uuid('product_id');
            $table->string('country_code', 2);
            $table->string('currency_code', 3);
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->decimal('tax', 5, 2)->nullable();
            $table->primary(['product_id', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_countries');
    }
};
