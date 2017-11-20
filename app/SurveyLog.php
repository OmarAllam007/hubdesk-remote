<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyLog extends Model
{
    protected $fillable = ['ticket_id','survey_id','question_id','answer_id','comment'];
}
