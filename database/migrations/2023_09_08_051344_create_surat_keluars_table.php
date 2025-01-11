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
            $table->string('no_surat', 190)->nullable();
            $table->integer('no_indeks')->nullable();
            $table->integer('no_sub_indeks')->nullable();
            $table->string('perihal', 190);
            $table->string('asal', 190);
            $table->string('pola', 190)->nullable();
            $table->string('tujuan', 190)->nullable();
            $table->string('ringkasan', 190)->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->boolean('is_diajukan')->default(false);
            $table->boolean('is_diterima')->nullable();
            $table->string('verifikator', 190)->nullable();
            $table->text('catatan')->nullable();

            $table->foreignId('klasifikasi_surat_id')->nullable();
            $table->foreign('klasifikasi_surat_id')->references('id')->on('klasifikasi_surats')->restrictOnDelete();

            $table->foreignId('pola_spesimen_id');
            $table->foreign('pola_spesimen_id')->references('id')->on('pola_spesimens')->restrictOnDelete();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['no_indeks', 'no_sub_indeks', 'pola_spesimen_id'], 'surat_keluar_unique');
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
