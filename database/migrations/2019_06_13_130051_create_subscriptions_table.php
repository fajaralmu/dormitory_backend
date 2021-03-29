<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->uuid('id');
            // $table->integer('user_id');
            $table->uuid('sekolah_id');
            $table->string('paket');
            $table->string('kupon')->nullable();
            $table->text('data_pegawai_filename')->nullable();
            $table->json('history')->nullable();
            $table->boolean('active')->nullable()->default(0);
            $table->date('active_hingga')->nullable();
            $table->integer('jumlah_siswa')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
