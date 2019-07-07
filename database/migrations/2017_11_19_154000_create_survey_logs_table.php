<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyLogsTable extends Migration
{

    public function up()
    {
        Schema::create('user_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->integer('survey_id');
            $table->string('comment');
            $table->tinyInteger('is_submitted')->nullable();
            $table->integer('notified');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('user_surveys');
    }
}
