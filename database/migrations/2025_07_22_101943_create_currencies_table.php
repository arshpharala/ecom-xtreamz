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
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code', 3)->unique();
                $table->string('name')->unique();
                $table->string('symbol');
                $table->smallInteger('decimal')->default(2)->nullable();
                $table->string('group_separator', 10)->default(',')->nullable();
                $table->string('decimal_separator', 10)->default('.')->nullable();
                $table->enum('currency_position', ['Left', 'Right']);
                $table->timestamps();
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
