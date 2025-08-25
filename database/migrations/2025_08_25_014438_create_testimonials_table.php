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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('designation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable();
            $table->boolean('is_active')->default(0);
            $table->integer('position')->default(99);
            $table->integer('rating')->default(5);
            $table->timestamps();
        });

        Schema::create('testimonial_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('testimonial_id');
            $table->string('locale', 5)->index();
            $table->string('name');
            $table->text('description');
            $table->unique(['testimonial_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_translations');
        Schema::dropIfExists('testimonials');
    }
};
