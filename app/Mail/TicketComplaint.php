<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketComplaint extends Mailable
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
        $subject = 'Complaint on ticket #' . $this->ticket->id;
        $description = request()->get('complaint')["description"] ?? '';

        return $this->subject($subject)->markdown('emails.ticket.complaint', compact('ticket', 'description'));
    }
}
