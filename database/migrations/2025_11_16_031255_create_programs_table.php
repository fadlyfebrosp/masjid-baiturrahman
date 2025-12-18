<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['Zakat', 'Infak', 'Sedekah', 'Wakaf', 'Hibah']);
            $table->string('judul');
            $table->integer('min_donasi')->default(0);
            $table->json('custom_nominal')->nullable();
            $table->unsignedBigInteger('target_dana')->nullable();
            $table->date('target_waktu')->nullable();
            $table->boolean('open_goals')->default(false);
            $table->string('foto')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
