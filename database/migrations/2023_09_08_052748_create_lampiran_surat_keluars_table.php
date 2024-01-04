<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLampiranSuratKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lampiran_surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('upload_id');
            $table->foreign('upload_id')->references('id')->on('uploads')->restrictOnDelete();
            $table->foreignId('surat_keluar_id');
            $table->foreign('surat_keluar_id')->references('id')->on('surat_keluars')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lampiran_surat_keluars');
    }
}
