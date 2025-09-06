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
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('link');
            $table->string('icon');
            $table->integer('position');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('announcement_translations', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('announcement_id');
            $table->string('locale', 5)->index();
            $table->text('title');
            $table->text('description')->nullable();
            $table->unique(['announcement_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_translations');
        Schema::dropIfExists('announcements');
    }
};
