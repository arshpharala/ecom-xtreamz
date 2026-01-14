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
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('return_reason_id')->constrained('return_reasons');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, shipped, received, refunded
            $table->string('shipping_cost_borne_by')->nullable(); // company, customer
            $table->string('refund_method')->nullable(); // original_payment, account_credits
            $table->string('refund_status')->default('pending'); // pending, completed
            $table->text('admin_notes')->nullable();
            
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
