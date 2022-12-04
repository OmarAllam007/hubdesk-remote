<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = ['name', 'letter_group_id', 'order', 'view_path', 'icon_path'];

    const PAYMENT_TYPES = [1 => 'Transfer to bank', 2 => 'Deduction from salary'];

    const TRANSFER_TO_BANK = 1;
    const DEDUCTION_FROM_SALARY = 2;

    function group()
    {
        return $this->belongsTo(LetterGroup::class, 'letter_group_id');
    }

    function fields()
    {
        return $this->hasMany(LetterField::class);
    }

    const SALARY_TRANSFER_TYPE = 3;
}
