<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Semua kolom SUDAH ADA → gunakan change()
            $table->string('payment_method')->nullable()->change();
            $table->string('payment_type')->nullable()->change();
            $table->string('payment_channel')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->string('payment_method')->nullable(false)->change();
            $table->string('payment_type')->nullable(false)->change();
            $table->string('payment_channel')->nullable(false)->change();
        });
    }
};
