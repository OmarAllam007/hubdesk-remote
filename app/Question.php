<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['id','description','survey_id','degree'];

    function answers(){
        return $this->hasMany(Answer::class);
    }

    function getDefaultAnswerAttribute(){
        return $this->answers()->where('is_default',1)->first();
    }
}
