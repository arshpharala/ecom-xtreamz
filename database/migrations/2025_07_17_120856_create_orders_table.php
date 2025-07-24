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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_number')->unique();
            $table->integer('user_id');
            $table->integer('billing_address_id');
            $table->string('email')->nullable(); // for guests
            $table->enum('payment_method', ['card', 'paypal']);
            $table->string('payment_status')->default('pending');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
