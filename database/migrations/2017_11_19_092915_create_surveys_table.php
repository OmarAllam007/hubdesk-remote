<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('category_survey', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('survey_id');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('surveys');
        Schema::dropIfExists('category_survey');
    }
}
