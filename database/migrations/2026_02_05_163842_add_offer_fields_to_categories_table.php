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
            $table->enum('discount_type', ['fixed', 'percent'])->nullable()->after('background_color');
            $table->decimal('discount_value', 15, 2)->nullable()->after('discount_type');
            $table->boolean('valid_forever')->default(true)->after('discount_value');
            $table->dateTime('valid_till')->nullable()->after('valid_forever');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'valid_forever', 'valid_till']);
        });
    }
};
