<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KGS\Document;

class DocumentTicket extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'document_id'];

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    function document(){
        return $this->belongsTo(Document::class);
    }
}
