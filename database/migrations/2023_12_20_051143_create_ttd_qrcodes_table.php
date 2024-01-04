<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTtdQrcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ttd_qrcodes', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->date('tanggal');
            $table->string('no_surat');
            $table->string('perihal');
            $table->string('pejabat');
            $table->string('jabatan');
            $table->string('file');
            $table->string('qrcode')->nullable();
            // $table->text('file_detail');
            $table->boolean('is_diterima')->nullable();
            $table->text('catatan')->nullable();

            $table->foreignId('user_ttd_id');
            $table->foreign('user_ttd_id')->references('id')->on('users')->restrictOnDelete();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['kode'], 'kode_qrcode_unique');
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
        Schema::dropIfExists('ttd_qrcodes');
    }
}
