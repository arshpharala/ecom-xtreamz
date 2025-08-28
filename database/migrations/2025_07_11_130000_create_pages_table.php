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
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->text('banner')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('page_id')->index();
            $table->string('locale', 5)->index();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->timestamps();
            $table->unique(['page_id', 'locale']);
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('page_id')->index();
            $table->string('type'); // e.g. hero, features
            $table->string('image')->nullable(); // single shared image
            $table->json('settings')->nullable(); // optional configs
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_section_translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('page_section_id')->index();
            $table->string('locale', 5)->index();
            $table->string('heading')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->unique(['page_section_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_section_translations');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
