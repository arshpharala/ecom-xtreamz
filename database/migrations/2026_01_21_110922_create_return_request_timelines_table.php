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
        Schema::create('return_request_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->cascadeOnDelete();
            $table->string('actor_type'); // 'admin', 'user', 'system'
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('title');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['actor_type', 'actor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_request_timelines');
    }
};
