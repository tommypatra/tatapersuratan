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
            $table->string('kode', 190)->nullable();
            $table->date('tanggal');
            $table->string('no_surat', 190);
            $table->string('perihal', 190);
            $table->string('pejabat', 190);
            $table->string('jabatan', 190);
            $table->string('file', 190);
            $table->string('qrcode', 190)->nullable();
            // $table->text('file_detail');

            $table->boolean('is_diajukan')->default(false);
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
