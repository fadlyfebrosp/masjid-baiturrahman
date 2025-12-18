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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donasi_id')->constrained()->cascadeOnDelete();

            $table->string('reference');
            $table->string('payment_method');
            $table->string('payment_code')->nullable();
            $table->string('pay_url')->nullable();

            $table->bigInteger('amount');
            $table->enum('status', ['pending', 'paid', 'expired', 'failed'])
                ->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
