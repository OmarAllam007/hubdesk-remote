<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = ['name', 'letter_group_id', 'order', 'view_path', 'icon_path'];

    function group()
    {
        return $this->belongsTo(LetterGroup::class,'letter_group_id');
    }

    function fields(){
        return $this->hasMany(LetterField::class);
    }

    const SALARY_TRANSFER_TYPE = 3;
}
