<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Collection survey_answers
 */
class UserSurvey extends Model
{
    protected $table = "user_surveys";

    protected $fillable = ['ticket_id', 'survey_id', 'comment', "is_submitted", "notified"];

    function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }


    function survey_answers()
    {
        return $this->hasMany(UserSurveyAnswer::class, 'user_survey_id');
    }

    function scopeNotSubmitted($query)
    {
        return $query->whereNotNull('is_submitted')->orWhere('is_submitted', 0);
    }

    function getTotalScoreAttribute()
    {
        if (!$this->is_submitted) {
            return 0;
        }

        return $this->survey_answers->filter(function ($answer) {
            return $answer->answer;
        })->average('answer.degree');


    }

    static function createSurvey($reply){
        if (($reply->status_id == 8 && $reply->requester->email) && $reply->category->survey->first()) {
            $survey = UserSurvey::create([
                'ticket_id' => $reply->ticket_id,
                'survey_id' => $reply->ticket->category->survey->first()->id,
                'comment' => '',
                'is_submitted' => 0,
                'notified' => 1
            ]);
            return $survey;
        }
    }

}
