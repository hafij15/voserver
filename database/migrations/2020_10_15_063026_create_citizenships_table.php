<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizenshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizenships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('dob');
            $table->string('nid')->nullable();
            $table->string('passport')->nullable();
            $table->string('gender');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->integer('updated_by')->default(0);

            //FOREIGN KEY CONSTRAINTS
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('occupation_id');
            $table->foreign('occupation_id')->references('id')->on('occupations')->onDelete('cascade');

            $table->unsignedBigInteger('citizenship_country_id');
            $table->foreign('citizenship_country_id')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('citizenships');
    }
}
