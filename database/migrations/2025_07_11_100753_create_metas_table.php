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
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('metable');
            $table->string('locale', 10)->index();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();

            $table->unique(['metable_id', 'metable_type', 'locale'], 'meta_unique');
        });

        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->timestamps();
        });

        Schema::create('meta_keyword', function (Blueprint $table) {
            $table->unsignedBigInteger('meta_id');
            $table->unsignedBigInteger('keyword_id');
            $table->primary(['meta_id', 'keyword_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('meta_keyword');
    }
};
