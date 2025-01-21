<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenSuratmasuk extends Migration
{
    public function up()
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->string('token')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
}
