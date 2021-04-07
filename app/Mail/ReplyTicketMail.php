<?php

namespace App\Mail;

use App\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplyTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var TicketReply
     */
    private $reply;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ticket = $this->reply->ticket;
        $reply = $this->reply;

        $subject = 'Re: Ticket #' . $ticket->id;

        if ($ticket['requester']['employee_id']) {
            $subject .= ' / ' . $ticket['requester']['employee_id'];
        }
        $subject .= '- ' . $ticket['subject'];

        return $this->markdown('emails.ticket.reply', compact('ticket','reply'))->subject($subject);
    }
}
