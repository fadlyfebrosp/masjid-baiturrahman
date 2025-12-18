<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritadankegiatanTable extends Migration
{
    public function up()
    {
        Schema::create('beritadankegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('namamasjid');
            $table->date('tanggal');
            $table->string('kategori');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beritadankegiatan');
    }
}
