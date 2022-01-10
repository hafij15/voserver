<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWbsMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wbs_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wbs_master_assignee_id');
            $table->foreign('wbs_master_assignee_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('reporter');
            $table->foreign('reporter')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('wbs_master_project_plan_details_id');
            $table->foreign('wbs_master_project_plan_details_id')->references('id')->on('project_plan_details')->onDelete('cascade');
            $table->unsignedBigInteger('wbs_master_wbs_id');
            $table->foreign('wbs_master_wbs_id')->references('id')->on('wbs')->onDelete('cascade');
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
        Schema::dropIfExists('wbs_masters');
    }
}
