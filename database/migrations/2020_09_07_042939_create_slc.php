<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slc')->nullable();
            $table->string('name')->nullable();
            $table->string('monthly_rate')->nullable();
            $table->string('hourly_rate')->nullable();
            $table->string('planned_hours')->nullable();
            $table->string('planned_value')->nullable();
            $table->string('budget')->nullable();
            $table->string('ep')->nullable();
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
        Schema::dropIfExists('slc');
    }
}
