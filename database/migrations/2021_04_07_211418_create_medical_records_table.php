<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->integer('temperature_morning')->nullable();
            $table->integer('temperature_afternoon')->nullable();
            $table->boolean('breakfast')->nullable();
            $table->boolean('lunch')->nullable();
            $table->boolean('dinner')->nullable();
            $table->boolean('medicine_consumption');
            $table->boolean('genose_test')->nullable();
            $table->boolean('antigen_test')->nullable();
            $table->boolean('pcr_test')->nullable();
            $table->text('description')->nullable();
            $table->uuid('student_id');
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
        Schema::dropIfExists('medical_records');
    }
}
