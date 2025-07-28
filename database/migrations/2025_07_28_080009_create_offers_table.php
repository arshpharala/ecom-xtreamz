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
        Schema::create('offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('discount_type', ['fixed', 'percent'])->default('percent');
            $table->decimal('discount_value', 10, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('offer_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('offer_id')->index();
            $table->string('locale', 5)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['offer_id', 'locale']);
        });

        Schema::create('offer_product_variants', function (Blueprint $table) {
            $table->uuid('offer_id');
            $table->uuid('product_variant_id');
            $table->unique(['offer_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_product_variants');
        Schema::dropIfExists('offer_translations');
        Schema::dropIfExists('offers');
    }
};
