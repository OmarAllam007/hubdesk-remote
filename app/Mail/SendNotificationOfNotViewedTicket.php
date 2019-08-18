<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationOfNotViewedTicket extends Mailable
{
    use Queueable, SerializesModels;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        $ticket =$this->ticket;
        $subject = "Reminder On Create New Ticket #".$ticket->id;
        return $this->markdown('emails.ticket.reminder_on_new_ticket',['ticket'=>$ticket])->subject($subject);
    }
}
