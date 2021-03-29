<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id');
            $table->uuid('kelas_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('nik')->nullable();
            $table->string('nisn');
            $table->string('nis');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('daerah_asal')->nullable();
            $table->string('alamat')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara')->nullable();
            $table->string('sekolah_asal')->nullable();
            $table->string('nomor_ijazah')->nullable();
            // $table->string('kelas');
            // $table->string('rombel')->nullable();
            $table->longtext('riwayat_kelas')->nullable();
            $table->string('telp')->nullable();
            $table->boolean('graduated')->default(0)->nullable();
            $table->boolean('is_beasiswa')->default(0)->nullable();
            $table->integer('wali_id')->nullable();
            $table->string('virtual_account')->nullable();
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
        Schema::dropIfExists('siswa');
    }
}
