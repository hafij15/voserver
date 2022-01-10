<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('plan_title');
            $table->string('labors')->nullable();
            $table->string('planned_hours')->nullable();
            $table->string('planned_ep')->nullable();
            $table->unsignedBigInteger('project_plan_work_package_id');          
            $table->foreign('project_plan_work_package_id')->references('id')->on('work_packages')->onDelete('cascade');
            $table->string('project_plan_assignee');
            // $table->foreign('project_plan_assignee')->references('id')->on('users')->onDelete('cascade');
            $table->date('planned_delivery_date')->nullable();
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
        Schema::dropIfExists('project_plans');
    }
}
