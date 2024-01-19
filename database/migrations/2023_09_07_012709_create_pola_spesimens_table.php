<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolaSpesimensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pola_spesimens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('pola_surat_id');
            $table->foreign('pola_surat_id')->references('id')->on('pola_surats')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('spesimen_jabatan_id');
            $table->foreign('spesimen_jabatan_id')->references('id')->on('spesimen_jabatans')->restrictOnDelete();
            $table->unique(['pola_surat_id', 'spesimen_jabatan_id'], "pola_spesimen_unik");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pola_spesimens');
    }
}
