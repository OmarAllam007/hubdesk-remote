<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterField extends Model
{
    protected $fillable = ['letter_id', 'name', 'type', 'options'];

    const TYPES = [1 => 'text-field', 'select-field', 'checkbox', 'date', 'datetime'];

//    protected $casts = ['options' => 'array'];
}
