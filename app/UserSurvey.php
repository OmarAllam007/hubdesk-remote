<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    protected $table = "user_surveys";
    protected $fillable = ['ticket_id', 'survey_id', 'comment', "is_submitted", "notified"];

    function survey()
    {
        return $this->belongsTo(Survey::class,'survey_id');
    }

    function survey_answers()
    {
        return $this->hasMany(UserSurveyAnswer::class, 'user_survey_id');
    }

    function scopeNotSubmitted($query)
    {
        return $query->whereNotNull('is_submitted')->orWhere('is_submitted', 0);
    }
}
