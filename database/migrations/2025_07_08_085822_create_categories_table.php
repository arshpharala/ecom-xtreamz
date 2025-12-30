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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->uuid('parent_id')->nullable()->index();
            $table->integer('position')->default(0);
            $table->string('menu_tag', 100)->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('category_id')->index();
            $table->string('locale', 5)->index();
            $table->string('name');
            $table->unique(['category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_translations');
    }
};
