<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAksesPolasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akses_polas', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->timestamps();
            $table->foreignId('pola_spesimen_id');
            $table->foreign('pola_spesimen_id')->references('id')->on('pola_spesimens')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->index('tahun');
            $table->unique(['tahun', 'pola_spesimen_id', 'user_id'], "akses_pola_unik");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akses_polas');
    }
}
