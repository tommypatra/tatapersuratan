<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_agenda', 190);
            $table->string('no_surat', 190);
            $table->string('perihal', 190);
            $table->string('asal', 190);
            $table->string('tempat', 190);
            $table->string('ringkasan', 190)->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->boolean('is_diajukan')->default(false);
            $table->boolean('is_diterima')->nullable();
            $table->string('verifikator', 190)->nullable();
            $table->text('catatan')->nullable();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();

            $table->foreignId('kategori_surat_masuk_id');
            $table->foreign('kategori_surat_masuk_id')->references('id')->on('kategori_surat_masuks')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_masuks');
    }
}
