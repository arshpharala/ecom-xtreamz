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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('author');
            $table->boolean('is_guide')->default(0);
            $table->boolean('is_active')->default(0);
            $table->integer('position')->default(99);
            $table->timestamp('published_at')->default(now());
            $table->string('thumbnail')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('news_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('news_id');
            $table->string('locale', 5)->index();
            $table->text('title');
            $table->text('intro');
            $table->text('description');
            $table->unique(['news_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
