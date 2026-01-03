<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            // kolom SUDAH ADA → change
            $table->string('payment_method')->nullable()->change();

            // kolom BELUM ADA → add
            $table->string('payment_type')->nullable()->after('payment_method');
            $table->string('payment_channel')->nullable()->after('payment_type');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->string('payment_method')->nullable(false)->change();

            $table->dropColumn([
                'payment_type',
                'payment_channel',
            ]);
        });
    }
};
