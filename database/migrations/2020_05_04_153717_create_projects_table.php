<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_name');
            $table->string('project_code');
            $table->unsignedBigInteger('project_lead_id');
            $table->string('support_engg_id');
            $table->foreign('project_lead_id')->references('id')->on('users')->onDelete('cascade');           
            $table->string('project_client')->nullable();
            $table->text('project_summary')->nullable();
            $table->string('project_file_dir')->nullable();
            $table->timestamp('project_start_date')->nullable();
            $table->timestamp('project_end_date')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
