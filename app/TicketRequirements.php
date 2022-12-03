<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use KGS\Requirement;

class TicketRequirements extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ticket_id', 'requirement_id', 'path', 'uploaded_by'];


    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    function requirement(){
        return $this->belongsTo(GrRequirements::class,'requirement_id');
    }
}
