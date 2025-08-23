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
        Schema::create('inventory_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 40)->unique();          // e.g. DUBAI, ABU_DHABI
            $table->string('name', 120);
            $table->text('description')->nullable();

            // Contact
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_fax')->nullable();

            // Address
            $table->integer('country_id');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->string('street', 160)->nullable();
            $table->string('postcode', 40)->nullable();

            // Settings
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->unsignedInteger('priority')->default(10); // lower = preferred
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('inventory_source_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inventory_source_id')->index();
            $table->uuid('product_variant_id')->index();
            $table->integer('qty')->default(0);
            $table->timestamps();

            $table->unique(['inventory_source_id', 'product_variant_id'], 'inventory_source_stock_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_source_stocks');
        Schema::dropIfExists('inventory_sources');
    }
};
