<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyLogsTable extends Migration
{

    public function up()
    {
        Schema::create('survey_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->integer('survey_id');
            $table->integer('question_id');
            $table->integer('answer_id');
            $table->string('comment');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('survey_logs');
    }
}
