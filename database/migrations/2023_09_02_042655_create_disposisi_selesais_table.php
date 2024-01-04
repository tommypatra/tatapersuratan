<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposisiSelesaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisi_selesais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('tujuan_id');
            $table->foreign('tujuan_id')->references('id')->on('tujuans')->restrictOnDelete();
            $table->unique('tujuan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposisi_selesais');
    }
}
