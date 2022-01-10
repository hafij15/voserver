<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pm_id')->comment('Host_id');
            $table->foreign('pm_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('patient_id');
            // $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('slot_id');
            // $table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade');           
            // $table->string('patient_symptoms')->nullable();
            $table->unsignedBigInteger('subtask_id')->nullable();
            $table->foreign('subtask_id')->references('id')->on('sub_tasks')->onDelete('cascade');
            $table->string('participant_id');
            $table->string('agenda')->nullable();
            $table->string('room_id');
            // $table->string('prescribe_medicines')->nullable();
            $table->string('spent_hour')->nullable();
            // $table->string('investigation')->nullable();
            // $table->string('cc')->nullable();
            // $table -> integer('isbooked')->default(0);
            $table -> integer('isServiced')->default(0);
            $table -> integer('isCancelled')->default(0);
            // $table -> integer('isApproved')->default(0);
            $table -> integer('approvedBy')->default(0);
            $table->dateTime('meeting_dateTime', 6);
            $table->text('meeting_mins')->nullable();
            // $table->date('follow_up_visit_date', 20)->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
