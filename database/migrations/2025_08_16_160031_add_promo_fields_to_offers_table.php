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
        Schema::table('offers', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('is_active'); // storage path (e.g. promos/abc.png)
            $table->string('link_url')->nullable()->after('banner_image');  // optional external/internal link
            $table->string('bg_color', 32)->nullable()->after('link_url');  // e.g. #fde7e8
            $table->integer('position')->default(0)->after('bg_color');     // sort
            $table->boolean('show_in_slider')->default(false);
            $table->index(['is_active', 'starts_at', 'ends_at']);
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropIndex(['offers_is_active_starts_at_ends_at_index']);
            $table->dropIndex(['offers_position_index']);
            $table->dropColumn(['banner_image', 'link_url', 'bg_color', 'position', 'show_in_slider']);
        });
    }
};
