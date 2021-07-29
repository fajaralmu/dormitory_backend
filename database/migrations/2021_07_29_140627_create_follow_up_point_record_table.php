<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowUpPointRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_up_point_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('point_record_id');
            $table->unsignedBigInteger('follow_up_id');
            $table->foreign('follow_up_id')->references('id')->on('follow_up');
            $table->foreign('point_record_id')->references('id')->on('point_records');
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
        Schema::dropIfExists('point_record_follow_up');
    }
}
