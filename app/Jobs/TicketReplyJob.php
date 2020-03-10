<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Mail\SendSurveyEmail;
use App\TicketLog;
use App\TicketReply;
use App\UserSurvey;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TicketReplyMail;

class TicketReplyJob extends Job //implements ShouldQueue
{
    //use InteractsWithQueue, SerializesModels;

    /**
     * @var TicketReply
     */
    protected $reply;
    protected $to;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    public function handle()
    {
        $ticket = $this->reply->ticket;

        if ($this->reply->user_id == $this->reply->ticket->requester_id) {

            if (!$ticket->technician || !$ticket->technician->email) {
                return false;
            }
            $this->to = [$ticket->technician->email];
            $this->sendEmail();

        } elseif ($this->reply->user_id == $this->reply->ticket->technician_id) {
            if (!$ticket->requester->email) {
                return false;
            }
            $this->to = [$ticket->requester->email];
            $this->sendEmail();

        } elseif ($this->reply->user_id != $this->reply->ticket->technician_id && $this->reply->user->isTechnician()) {
            if ($ticket->requester->email) {
                $this->to[] = $ticket->requester->email;
            }

            if ($ticket->technician->email) {
                $this->to[] = $ticket->technician->email;
            }

            $this->sendEmail();
        }
    }

    function sendEmail()
    {
        $cc = request('reply.cc',[]);

        \Mail::to($this->to)->cc($cc)->send(new TicketReplyMail($this->reply));
        $this->sendSurvey($this->reply->ticket);
    }

    private function sendSurvey($ticket)
    {
        if (($this->reply->status_id == 8 && $ticket->requester->email) && $ticket->category->survey->first()) {
            $survey = UserSurvey::create([
                'ticket_id' => $ticket->id,
                'survey_id' => $ticket->category->survey->first()->id,
                'comment' => '',
                'is_submitted' => 0,
                'notified' => 1
            ]);

            if ($survey->ticket->requester->email) {
                \Mail::send(new SendSurveyEmail($survey));
            }
            TicketLog::addReminderOnSurvey($ticket);
        }
    }

}
