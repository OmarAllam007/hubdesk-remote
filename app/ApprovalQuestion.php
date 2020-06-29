<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalQuestion extends Model
{
    protected $fillable = ['description', 'approval_id', 'answer'];

    const APPROVED = 1;
    const DENIED = -1;

    const status = [1 => 'Approved', -1 => 'Denied'];

    function getAnswerStrAttribute()
    {
        if(!isset(self::status[$this->answer])){
            return;
        }

        return self::status[$this->answer];
    }

    function getColorAttribute()
    {
        if ($this->answer == self::APPROVED) {
            return 'success';
        } elseif ($this->answer == self::DENIED) {
            return 'danger';
        }
        return '';
    }

}
