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
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2);

            $table->enum('type', ['cart', 'variant'])->default('cart');
            $table->boolean('is_active')->default(true);

            $table->decimal('min_cart_amount', 10, 2)->nullable();
            $table->boolean('first_time_only')->default(false);

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->integer('max_usage')->nullable();
            $table->integer('max_usage_per_user')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('coupon_product_variant', function (Blueprint $table) {
            $table->uuid('coupon_id');
            $table->uuid('product_variant_id');
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->uuid('coupon_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
