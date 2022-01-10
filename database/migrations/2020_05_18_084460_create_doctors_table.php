<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('clinic_id');
            // $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->unsignedBigInteger('department_id');          
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger('moderator_id');          
            $table->foreign('moderator_id')->references('id')->on('moderators')->onDelete('cascade');
            $table->integer('registration_no')->nullable();
            $table->string('educational_degrees');
            $table->string('visit_fee')->nullable();
            $table->boolean('isActiveForScheduling');
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
        Schema::dropIfExists('doctors');
    }
}
