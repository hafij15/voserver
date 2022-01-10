<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('work_package_number');
            $table->unique('work_package_number');
            $table->unsignedBigInteger('work_package_subtask_id');          
            $table->foreign('work_package_subtask_id')->references('id')->on('sub_tasks')->onDelete('cascade');
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
        Schema::dropIfExists('work_packages');
    }
}
