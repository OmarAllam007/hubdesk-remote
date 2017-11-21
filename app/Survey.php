<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['name'];

    function categories()
    {
        return $this->belongsToMany(Category::class, 'category_survey', 'survey_id', 'category_id');
    }

    function questions()
    {
        return $this->hasMany(Question::class);
    }

    function submittedBefore($ticket){
        return SurveyLog::where('ticket_id',$ticket->id)->where('survey_id',$this->id)->exists();
    }
}
