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
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('is_active')->default(true);
            $table->integer('postion')->default(99);
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('tag_product_variant', function (Blueprint $table) {
            $table->uuid('tag_id');
            $table->uuid('product_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_product_variant');
        Schema::dropIfExists('tags');
    }
};
