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
        Schema::create('emails', function (Blueprint $table) {
        $table->uuid('id')->primary();
            $table->string('reference');
            $table->string('template');
            $table->string('subject');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to_email')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('email_admin', function (Blueprint $table) {
            $table->uuid('email_id');
            $table->integer('admin_id');
            $table->enum('type', ['to', 'cc', 'bcc', 'exclude']);
        });

        Schema::create('email_user', function (Blueprint $table) {
            $table->uuid('email_id');
            $table->integer('user_id');
            $table->enum('type', ['to', 'cc', 'bcc', 'exclude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_user');
        Schema::dropIfExists('email_admin');
        Schema::dropIfExists('emails');
    }
};
