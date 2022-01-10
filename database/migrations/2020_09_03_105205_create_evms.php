<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('evms_tdo_id');          
            $table->foreign('evms_tdo_id')->references('id')->on('tdos')->onDelete('cascade');
            $table->string('bcws')->nullable();
            $table->string('pv')->nullable();
            $table->string('actual_cost')->nullable();
            $table->string('acwp')->nullable();
            $table->string('bac')->nullable();
            $table->string('ac')->nullable();
            $table->string('cv')->nullable();
            $table->string('pmb')->nullable();
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
        Schema::dropIfExists('evms');
    }
}
