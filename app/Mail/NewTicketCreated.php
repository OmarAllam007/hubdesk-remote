<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTicketCreated extends Mailable
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


    public function build()
    {
        $ticket = $this->ticket;
        return $this->markdown('emails.ticket.new_ticket',compact('ticket'))
            ->subject('A new ticket #' . $ticket->id . ' has been created for you')
            ->to($ticket->requester->email);
    }
}
