<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi_offlines', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->foreignId('program_id')
                ->constrained('programs')
                ->cascadeOnDelete();

            $table->foreignId('contactdonasioffline_id')
                ->constrained('contactdonasiofflines')
                ->cascadeOnDelete();

            // DATA TRANSAKSI
            $table->unsignedBigInteger('nominal');

            $table->enum('metode_pembayaran', [
                'CASH',
                'TRANSFER',
                'QRIS',
                'DEBIT',
                'LAINNYA'
            ])->default('CASH');

            $table->dateTime('tanggal_transaksi');

            // OPSIONAL TAPI DISARANKAN
            $table->string('kode_transaksi')->unique();
            $table->text('catatan')->nullable();

            $table->enum('status', [
                'PENDING',
                'SELESAI',
                'BATAL'
            ])->default('SELESAI');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi_offlines');
    }
};
