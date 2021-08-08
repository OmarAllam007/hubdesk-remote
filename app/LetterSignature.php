<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterSignature extends Model
{
    protected $fillable = ['letter_id', 'business_unit_id', 'user_id', 'order'];

    function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
