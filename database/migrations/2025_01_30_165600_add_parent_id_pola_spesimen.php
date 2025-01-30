<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdPolaSpesimen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pola_spesimens', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('pola_spesimens')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pola_spesimens', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
