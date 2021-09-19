<?php

namespace App\Mail;

use App\LetterTicket;
use App\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LetterRequestForFees extends Mailable
{
    use Queueable, SerializesModels;

    protected $letterTicket;
    protected $reply;

    /**
     * LetterRequestForFees constructor.
     * @param LetterTicket $letterTicket
     */
    public function __construct(LetterTicket $letterTicket,TicketReply $reply)
    {
        $this->letterTicket = $letterTicket;
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

        return $this->markdown('emails.letters.fee_reply', compact('ticket','reply'))->subject($subject);
    }
}
