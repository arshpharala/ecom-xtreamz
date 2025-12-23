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
        Schema::create('product_varaint_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('product_variant_id');
            $table->string('locale', 5)->index();
            $table->text('title');
            $table->text('description')->nullable();
            $table->unique(['product_variant_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_varaint_translations');
    }
};
