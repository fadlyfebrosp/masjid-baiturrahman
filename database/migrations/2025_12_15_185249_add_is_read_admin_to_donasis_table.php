<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom (JALAN SAAT MIGRATE)
     */
    public function up(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            $table->boolean('is_read_admin')
                  ->default(false)
                  ->after('status');
        });
    }

    /**
     * Hapus kolom (JALAN SAAT ROLLBACK)
     */
    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            $table->dropColumn('is_read_admin');
        });
    }
};
