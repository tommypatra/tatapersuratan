<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTujuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tujuans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu_akses')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            // $table->foreignId('created_by');
            // $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('surat_masuk_id');
            $table->foreign('surat_masuk_id')->references('id')->on('surat_masuks')->restrictOnDelete();
            // $table->unique(['surat_masuk_id', 'created_by']);
            $table->unique(['surat_masuk_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tujuans');
    }
}
