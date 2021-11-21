<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingTicketFormCreated extends Mailable
{
    use Queueable;

    private $ticket;
    private $fullName;


    public function __construct(Ticket $ticket, $fullName)
    {
        $this->ticket = $ticket;
        $this->fullName = $fullName;
    }


    public function build()
    {
        return $this->markdown('emails.training.request', ['ticket' => $this->ticket,
            'name' => $this->fullName])->subject("Internship Application");
    }
}
