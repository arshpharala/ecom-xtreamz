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
        Schema::create('countries', function (Blueprint $table) {
            $table->string('code', 2)->primary(); // ISO-2
            $table->string('name');
            $table->string('currency_code', 3);
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->string('code', 3)->primary(); // ISO-3
            $table->string('name');
            $table->decimal('exchange_rate', 12, 6)->default(1.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
