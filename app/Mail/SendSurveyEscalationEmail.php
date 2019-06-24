<?php

namespace App\Mail;

use App\Ticket;
use App\UserSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSurveyEscalationEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $survey;
    private $ticket;

    public function __construct(UserSurvey $survey)
    {
        $this->survey = $survey;
        $this->ticket = $this->survey->ticket;
    }

    public function build()
    {
        if($this->ticket->group && $this->ticket->group->supervisors){
            return $this->markdown('emails.ticket.escalation_survey', ['survey' => $this->survey])
                ->to($this->ticket->group->supervisors->pluck('email')->toArray())
                ->subject(t('Escalation of not-satisfied Survey - Ticket#'.$this->ticket->id));
        }
    }
}
