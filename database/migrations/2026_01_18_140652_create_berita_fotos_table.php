<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritaFotosTable extends Migration
{
    public function up()
    {
        Schema::create('berita_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_dan_kegiatan_id')
                ->constrained('beritadankegiatan')
                ->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('berita_fotos');
    }
}
