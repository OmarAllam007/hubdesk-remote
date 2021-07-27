<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterField extends Model
{
    protected $fillable = ['letter_id', 'name', 'type'];

    const TYPES = [1 => 'text', 'list', 'checkbox', 'date', 'datetime'];



}
