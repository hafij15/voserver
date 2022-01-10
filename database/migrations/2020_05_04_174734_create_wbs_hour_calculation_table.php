<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWbsHourCalculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wbs_hour_calculation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wbs_id');
            $table->foreign('wbs_id')->references('id')->on('wbs')->onDelete('cascade');
            $table->unsignedBigInteger('wbs_user_id');
            $table->foreign('wbs_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('wbs_date')->nullable();
            $table->string('wbs_hour')->nullable();
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
        Schema::dropIfExists('wbs_hour_calculation');
    }
}
