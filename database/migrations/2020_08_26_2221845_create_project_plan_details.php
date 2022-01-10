<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPlanDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_plan_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('project_task_details')->nullable();
            $table->unsignedBigInteger('project_plan_project_plan_id');          
            $table->foreign('project_plan_project_plan_id')->references('id')->on('project_plans')->onDelete('cascade');
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
        Schema::dropIfExists('project_plan_details');
    }
}
