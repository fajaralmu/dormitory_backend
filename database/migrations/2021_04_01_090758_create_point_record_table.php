<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->string('time');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->uuid('student_id');
            $table->unsignedBigInteger('point_id');
            $table->foreign('point_id')->references('id')->on('rule_points');
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
        Schema::dropIfExists('point_record');
    }
}
