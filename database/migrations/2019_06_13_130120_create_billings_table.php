<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id');
            // $table->integer('user_id');
            $table->uuid('subcription_id');
            $table->string('kode');
            $table->string('paket');
            $table->integer('harga');
            $table->string('durasi_layanan');
            $table->date('batas_akhir_pembayaran');
            $table->integer('nominal_tagihan');
            $table->integer('nominal_dibayar')->nullable();
            $table->integer('saldo')->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->longText('bukti_pembayaran')->nullable();
            $table->string('voucher')->nullable();
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
        Schema::dropIfExists('billings');
    }
}
