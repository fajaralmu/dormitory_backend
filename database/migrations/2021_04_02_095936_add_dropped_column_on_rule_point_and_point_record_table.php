<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDroppedColumnOnRulePointAndPointRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_records', function (Blueprint $table) {
            $table->timestamp('dropped_at')->nullable();
        });
        Schema::table('rule_points', function (Blueprint $table) {
            $table->boolean('droppable')->nullable();
        });
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
