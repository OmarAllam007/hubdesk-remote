<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTicket extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'document_id'];

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
