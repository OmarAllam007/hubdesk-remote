<?php

namespace App\Mail;

use App\Jobs\ApplyBusinessRules;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        $apply = new ApplyBusinessRules($this->ticket);
//        $apply->fetchBusinessRule();
//        $cc = $apply->ccEmails->count() ? $apply->ccEmails : [];

        $ticket = $this->ticket;

        $subject = 'Ticket #' . $ticket->id . ' has been assigned to you';
        return $this->markdown('emails.ticket.assigned',compact('ticket'))
            ->subject($subject)
            ->to($ticket->technician->email)->cc([]);
    }
}
