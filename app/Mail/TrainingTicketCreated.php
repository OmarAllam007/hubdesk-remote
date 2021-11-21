<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingTicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return Ticket $ticket
     */
    protected $ticket;

    public function __construct(Ticket $ticket)
    {

        $this->ticket = $ticket;
    }


    public function build()
    {

        $subject = 'Internship Request Acknowledgement';
//        $ticket = $this->ticket;

        return $this->markdown('emails.training.request')
            ->subject($subject)
            ->to(\request('email'));
    }
}
