<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('time_card_wbs_id');
            $table->integer('time_card_wbs_assignee_id');
            $table->integer('time_card_wbs_master_id');
            $table->integer('time_card_project_plan_id');
            $table->integer('time_card_work_package_id');
            $table->integer('time_card_subtask_id');
            $table->text('time_card_wbs_task_details');
            $table->char('time_card_plan_title');
            $table->integer('time_card_actual_hours_today');
            $table->string('task_status');
            $table->date('wbs_update_date');
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
        Schema::dropIfExists('time_card');
    }
}
