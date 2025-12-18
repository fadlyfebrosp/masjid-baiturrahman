<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontakinformasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aplikasi');
            $table->text('alamat_lengkap');
            $table->string('email')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('nomor_whatsapp');
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontakinformasis');
    }
};
