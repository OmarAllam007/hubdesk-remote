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
    private $complaint;

    public function __construct(Ticket $ticket , $complaint)
    {
        $this->ticket = $ticket;
        $this->complaint = $complaint;
    }


    public function build()
    {
        $ticket = $this->ticket;
        $subject = 'Complaint on ticket #' . $this->ticket->id;
        $complaint = $this->complaint;


        return $this->subject($subject)->markdown('emails.ticket.complaint', compact('ticket', 'complaint'));
    }
}
