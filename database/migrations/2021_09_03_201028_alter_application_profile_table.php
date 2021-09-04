<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterApplicationProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement('ALTER TABLE `application_profiles` ADD `division_head_id` VARCHAR(255) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `school_director_id` VARCHAR(255) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `report_date` VARCHAR(255) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `semester` INT(11) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `tahun_ajaran` VARCHAR(255) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `stamp` VARCHAR(255) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `division_head_signature` VARCHAR(255) default NULL');
        DB::statement('ALTER TABLE `application_profiles` ADD `school_director_signature` VARCHAR(255) default NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
