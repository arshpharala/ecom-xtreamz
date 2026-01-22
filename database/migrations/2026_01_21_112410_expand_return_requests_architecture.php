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
        Schema::table('return_requests', function (Blueprint $table) {
            $table->string('resolution_type')->nullable()->after('refund_reference'); // refund, replacement, store_credit
            $table->string('customer_tracking_number')->nullable()->after('resolution_type');
            $table->string('carrier_name')->nullable()->after('customer_tracking_number');
            $table->string('inspection_status')->default('pending')->after('carrier_name'); // pending, passed, failed
            $table->text('inspection_notes')->nullable()->after('inspection_status');
            $table->unsignedBigInteger('replacement_order_id')->nullable()->after('inspection_notes');
            $table->string('reason_category')->nullable()->after('return_reason_id'); // defective, wrong_item, size_issue, other

            $table->foreign('replacement_order_id')->references('id')->on('orders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropForeign(['replacement_order_id']);
            $table->dropColumn([
                'resolution_type',
                'customer_tracking_number',
                'carrier_name',
                'inspection_status',
                'inspection_notes',
                'replacement_order_id',
                'reason_category'
            ]);
        });
    }
};
