<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpesimenJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spesimen_jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 190)->nullable();
            $table->string('jabatan', 190);
            $table->string('keterangan', 190)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->foreignId('user_pejabat_id')->nullable();
            $table->foreign('user_pejabat_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spesimen_jabatans');
    }
}
