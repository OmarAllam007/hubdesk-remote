<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LabourOfficeUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_labour_office', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->string('ar_name');
            $table->string('nationality');
            $table->string('building_no');
            $table->string('building_name');
            $table->string('border_no');
            $table->string('iqama_number');
            $table->string('job');
            $table->string('iqama_expire_date');
            $table->string('ksa_entrance_date');
            $table->string('employee_type');
            $table->string('employee_status');
            $table->string('company');
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
        Schema::dropIfExists('users_labour_office');
    }
}
