<?php

namespace App\Mail;

use App\Ticket;
use App\User;
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
        $complaint = $this->ticket->complaint;

        if ($complaint) {
            $to = User::whereIn('id', $complaint->to)->get(['email']);
            $cc = User::whereIn('id', $complaint->cc)->get(['email']);

            if ($to) {
                return $this->markdown('emails.ticket.escalation_survey', ['survey' => $this->survey])
                    ->to($to)
                    ->cc($cc ?? [])
                    ->subject(t('Escalation of not-satisfied Survey - Ticket#' . $this->ticket->id));
            }
        }



    }
}
