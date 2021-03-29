<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSekolahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id');
            $table->uuid('yayasan_id')->nullable();
            $table->string('npsn');
            $table->string('nama');
            $table->string('jenjang');
            // $table->string('kepemilikan');
            $table->string('jenis');
            $table->text('alamat');
            $table->string('telp');
            $table->text('data_siswa_filename')->nullable();
            // $table->text('data_pegawai_filename')->nullable();
            $table->text('rombel')->nullable();
            // $table->integer('jumlah_peserta_didik');
            // $table->integer('jumlah_pegawai');
            $table->json('options')->nullable();
            $table->json('struktur_organisasi')->nullable();
            $table->text('api_token')->nullable();             
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
        Schema::dropIfExists('sekolah');
    }
}
