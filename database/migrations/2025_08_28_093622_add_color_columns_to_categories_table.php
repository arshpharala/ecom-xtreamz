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
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('show_on_homepage')->default(false)->after('is_visible');
            $table->string('background_color')->nullable()->after('icon');
            $table->string('text_color')->nullable()->after('background_color');
            $table->string('image')->nullable()->after('icon');
            $table->string('banner_image')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('background_color');
            $table->dropColumn('text_color');
            $table->dropColumn('show_on_homepage');
            $table->dropColumn('image');
            $table->dropColumn('banner_image');
        });
    }
};
