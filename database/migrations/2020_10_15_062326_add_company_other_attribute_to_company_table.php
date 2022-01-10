<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyOtherAttributeToCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->date('foundation_date')->nullable();
            $table->string('founded_by')->nullable();
            $table->string('licence_no')->nullable();
            $table->tinyInteger('activation_status')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('website');
            $table->dropColumn('foundation_date');
            $table->dropColumn('founded_by');
            $table->dropColumn('licence_no');
            $table->dropColumn('activation_status');
            $table->dropColumn('is_deleted');
        });
    }
}
