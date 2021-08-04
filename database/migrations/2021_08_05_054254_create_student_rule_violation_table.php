<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRuleViolationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_rule_violation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('student_id');

            $table->integer('semester');
            $table->string('tahun_ajaran');

            $table->integer('point');
            $table->string('name');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('student_rule_violation');
    }
}
