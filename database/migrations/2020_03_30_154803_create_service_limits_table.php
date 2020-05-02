<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_limits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('level_id');
            $table->string('level');
            $table->text('label');
            $table->text('value');
            $table->unsignedInteger('number_of_tickets');
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
        Schema::dropIfExists('service_limits');
    }
}
