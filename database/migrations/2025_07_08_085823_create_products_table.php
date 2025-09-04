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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('category_id')->index();
            $table->uuid('brand_id')->nullable()->index();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('show_in_slider')->default(false);
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('product_id')->index();
            $table->string('locale', 5)->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['product_id', 'locale']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id')->index();
            $table->string('thumbnail')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('product_variant_attribute_value', function (Blueprint $table) {
            $table->uuid('product_variant_id');
            $table->uuid('attribute_value_id');
            $table->primary(['product_variant_id', 'attribute_value_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_variant_attribute_value');
    }
};
