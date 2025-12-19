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
        Schema::table('brands', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id');
            $table->string('reference_name')->nullable()->after('reference_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id');
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->string('reference_name')->nullable()->after('category_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id');
            $table->string('reference_name')->nullable()->after('reference_id');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id');
            $table->string('reference_name')->nullable()->after('reference_id');
        });

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id')->index();
            $table->string('reference_value')->nullable()->after('reference_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id')->index();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('reference_id')->nullable()->after('id')->index();
            $table->string('reference_sku')->nullable()->after('reference_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['reference_id', 'reference_name']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['reference_id']);
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn(['reference_name']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['reference_id', 'reference_name']);
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn(['reference_id', 'reference_name']);
        });

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropColumn(['reference_id', 'reference_value']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['reference_id', 'reference_sku']);
        });
    }
};
