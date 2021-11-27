<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPermintaanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_permintaan_barangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_permintaan_barang')->unsigned();
            $table->string('kode_barang');
            $table->integer('qty')->unsigned();
            $table->string('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_permintaan_barangs');
    }
}
