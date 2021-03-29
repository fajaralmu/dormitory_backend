<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id');
            $table->uuid('yayasan_id')->nullable();
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('gender')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat')->nullable();
            $table->string('telp')->nullable();
            $table->text('jabatan')->nullable();
            $table->text('riwayat_jabatan')->nullable();
            $table->string('file_foto')->nullable();
            $table->integer('propinsi')->nullable();
            $table->integer('kabupaten_kota')->nullable();
            $table->integer('kecamatan')->nullable();
            $table->integer('kelurahan')->nullable();
            $table->integer('kodepos')->nullable();
            $table->string('agama')->nullable();
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
        Schema::dropIfExists('pegawai');
    }
}
