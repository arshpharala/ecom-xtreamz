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
        Schema::table('currencies', function (Blueprint $table) {
            $table->string('symbol_html');
            $table->boolean('is_default')->default(false);
            $table->decimal('exchange_rate', 10, 4)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('symbol_html');
            $table->dropColumn('is_default');
            $table->dropColumn('exchange_rate');
        });
    }
};
