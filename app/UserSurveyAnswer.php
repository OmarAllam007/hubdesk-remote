<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSurveyAnswer extends Model
{
    protected $table = "user_surveys_answers";

    protected $fillable = ["user_survey_id","question_id","answer_id"];

    function survey(){
        return $this->belongsTo(UserSurvey::class,"user_survey_id");
    }

    function answer(){
        return $this->belongsTo(Answer::class);
    }
}
