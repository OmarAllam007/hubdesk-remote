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
        $this->ticket = $reply->ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Re: Ticket #' . $this->ticket->id;

        if($this->ticket->requester->employee_id){
            $subject .= ' / '. $this->ticket->requester->employee_id;
        }
        $subject .= '- '. $this->ticket->subject;

        return $this->markdown('emails.ticket.reply', ['ticket' => $this->ticket, 'reply' => $this->reply])->subject($subject);
    }
}
