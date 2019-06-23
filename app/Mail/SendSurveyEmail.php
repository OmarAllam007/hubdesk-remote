<?php

namespace App\Mail;

use App\Ticket;
use App\UserSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSurveyEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->markdown('emails.ticket.survey', ['ticket' => $this->ticket])
            ->to($this->ticket->requester->email)
            ->subject(t('Please help us to improve our service by participating in this brief survey.'));
    }
}
