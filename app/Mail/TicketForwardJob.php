<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketForwardJob extends Mailable
{
    use Queueable, SerializesModels;

    private $ticket;
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    
    public function build()
    {
        $ticket = $this->ticket;
        $subject = $ticket->sdp_id ? '[Fwd: ##'.$ticket->sdp_id.'## : '.$ticket->subject.']' : '[Fwd: ##'.$ticket->id.'## : '.$ticket->subject.']';
        $description = request()->get('forward')["description"] ?? '';

        return $this->subject($subject)->markdown('emails.ticket.forward',compact('ticket','description'));
    }
}
