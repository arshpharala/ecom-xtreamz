<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('return_requests')->where('status', 'pending')->update(['status' => 'requested']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('return_requests')->where('status', 'requested')->update(['status' => 'pending']);
    }
};
