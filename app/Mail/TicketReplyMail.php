<?php

namespace App\Mail;

use App\Ticket;
use App\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reply;
    protected $ticket;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
//        $this->ticket = $reply->ticket;
    }

    public function build()
    {
//        $subject = 'Re: Ticket #' . $this->ticket->id;
//
//        if ($this->ticket->requester->employee_id) {
//            $subject .= ' / ' . $this->ticket->requester->employee_id;
//        }
//        $subject .= '- ' . $this->ticket->subject;

        $ticket = $this->reply->ticket;

        return $this->markdown('emails.ticket.assigned', compact('reply'))
            ->subject('here');
    }
}
