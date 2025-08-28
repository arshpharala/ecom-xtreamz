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
        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image');       // foreground image (product / person)
            $table->string('background');  // hero background

            $table->string('text_color');
            $table->string('btn_text')->nullable();
            $table->string('btn_color')->nullable();
            $table->string('btn_link')->nullable();

            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('banner_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('banner_id')->index();
            $table->string('locale', 5)->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['banner_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_translations');
        Schema::dropIfExists('banners');
    }
};
