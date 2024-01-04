<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->integer('no_indeks');
            $table->integer('no_sub_indeks')->nullable();
            $table->string('perihal');
            $table->string('asal');
            $table->string('pola')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('ringkasan')->nullable();
            $table->date('tanggal');
            $table->timestamps();

            // $table->foreignId('spesimen_jabatan_id');
            // $table->foreign('spesimen_jabatan_id')->references('id')->on('spesimen_jabatans')->restrictOnDelete();

            $table->foreignId('klasifikasi_surat_id')->nullable();
            $table->foreign('klasifikasi_surat_id')->references('id')->on('klasifikasi_surats')->restrictOnDelete();

            // $table->foreignId('pola_surat_id');
            // $table->foreign('pola_surat_id')->references('id')->on('pola_surats')->restrictOnDelete();

            $table->foreignId('akses_pola_id');
            $table->foreign('akses_pola_id')->references('id')->on('akses_polas')->restrictOnDelete();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['no_indeks', 'no_sub_indeks', 'akses_pola_id'], 'surat_keluar_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluars');
    }
}
