<?php

namespace App\Mail;

use App\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SAPResetPasswordEmail extends Mailable
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

        $field = $ticket->fields()->first()->value;
        return $this->markdown('emails.ticket.sap_reset_reply', compact('ticket', 'reply','field'))
            ->subject($subject);
    }
}
