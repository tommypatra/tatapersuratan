<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAksesDisposisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akses_disposisis', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->foreignId('spesimen_jabatan_id');
            $table->foreign('spesimen_jabatan_id')->references('id')->on('spesimen_jabatans')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->index('tahun');
            $table->unique(['tahun', 'spesimen_jabatan_id', 'user_id'], "akses_disposisi_unik");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akses_disposisis');
    }
}
