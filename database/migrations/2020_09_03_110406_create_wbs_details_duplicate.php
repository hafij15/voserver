<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWbsDetailsDuplicate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wbs_details_duplicate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wbs_details_duplicate_employee_id');
            $table->foreign('wbs_details_duplicate_employee_id')->references('id')->on('employee')->onDelete('cascade');
            $table->text('wbs_task_details')->nullable();
            $table->unsignedBigInteger('wbs_duplicate_wbs_master_id');
            $table->foreign('wbs_duplicate_wbs_master_id')->references('id')->on('wbs_masters')->onDelete('cascade');
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
        Schema::dropIfExists('wbs_details_duplicate');
    }
}
