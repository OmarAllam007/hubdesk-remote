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
    private $message;

    public function __construct(Ticket $ticket , $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    
    public function build()
    {
        $ticket = $this->ticket;
        $subject = $ticket->sdp_id ? '[Fwd: ##'.$ticket->sdp_id.'## : '.$ticket->subject.']' : '[Fwd: ##'.$ticket->id.'## : '.$ticket->subject.']';
        $description = $this->message ?? '';

        return $this->subject($subject)->markdown('emails.ticket.forward',compact('ticket','description'));
    }
}
