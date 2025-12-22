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
        Schema::create('api_sync_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('source');                // Jasani
            $table->string('endpoint');              // products / price / stock
            $table->string('url')->nullable();       // API or file source

            $table->unsignedInteger('total_records')->default(0);
            $table->boolean('success')->default(false);
            $table->integer('http_status')->nullable();

            $table->text('message')->nullable();
            $table->timestamp('fetched_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_sync_logs');
    }
};
