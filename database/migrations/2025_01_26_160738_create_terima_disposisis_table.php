<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerimaDisposisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terima_disposisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tujuan_id');
            $table->foreign('tujuan_id')->references('id')->on('tujuans')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['tujuan_id', 'user_id'], "terima_disposisi_unik");
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
        Schema::dropIfExists('terima_disposisis');
    }
}
