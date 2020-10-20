<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketNote extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'note', 'display_to_requester', 'email_to_technician', 'as_first_response', 'created_at', 'updated_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function convertToJson()
    {
        return [
            'id' => $this->id,
            'creator' => $this->creator->name ?? 'Not assigned',
            'note' => htmlspecialchars_decode($this->note),
            'can_display' => $this->display_to_requester || \Auth::user()->isSupport(),
            'created_at' => $this->created_at->format('d/m/Y H:i A'),
            'ticket_id' => $this->ticket->id,
        ];
    }
}
