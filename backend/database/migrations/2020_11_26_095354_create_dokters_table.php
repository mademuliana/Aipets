<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sertifikat');
            $table->bigInteger('no_ijin');
            $table->date('tanggal_sertifikat');
            $table->integer('id_user')->unique();
            $table->time('waktu_buka');
            $table->time('waktu_tutup');
            $table->integer('tipe');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dokters');
    }
}
