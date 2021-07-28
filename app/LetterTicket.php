<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterTicket extends Model
{
    protected $fillable = ['ticket_id', 'group_id', 'subgroup_id', 'letter_id', 'need_coc_stamp'];

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    function group()
    {
        return $this->belongsTo(LetterGroup::class, 'group_id');

    }

    function subgroup()
    {
        return $this->belongsTo(LetterGroup::class, 'subgroup_id');
    }

    function letter()
    {
        return $this->belongsTo(Letter::class, 'letter');
    }
}
