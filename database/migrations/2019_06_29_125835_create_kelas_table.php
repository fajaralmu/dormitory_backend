<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id');
            $table->uuid('sekolah_id');
            $table->string('jurusan');
            $table->integer('level');
            $table->string('rombel');
            $table->integer('jumlah_siswa');
            $table->string('tahun_ajaran');
            $table->uuid('pegawai_id');
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
        Schema::dropIfExists('kelas');
    }
}
