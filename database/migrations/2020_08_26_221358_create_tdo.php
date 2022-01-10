<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTdo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tdos', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedBigInteger('sub_task_id');
            // $table->foreign('sub_task_id')->references('id')->on('sub_tasks')->onDelete('cascade');
            $table->string('title');
            $table->text('details');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('tdos');
    }
}
