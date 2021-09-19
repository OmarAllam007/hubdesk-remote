<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Mail\ReplyTicketMail;
use App\Mail\SendSurveyEmail;
use App\Mail\TicketAssignedMail;
use App\Survey;
use App\TicketLog;
use App\TicketReply;
use App\User;
use App\UserSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TicketReplyMail;

class TicketReplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TicketReply
     */
    protected $reply;
    protected $to;
    protected $cc;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
        $this->cc = [];
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

            $this->to[] = $ticket->requester->email;

            if (($ticket->created_by->id != $ticket->requester->id) && $ticket->created_by->email) {
                $this->cc[] = $ticket->created_by->email;
            }

            $this->sendEmail();

        } elseif ($this->reply->user_id != $this->reply->ticket->technician_id && $this->reply->user->isTechnician()) {
            if ($ticket->requester->email) {
                $this->to[] = $ticket->requester->email;
            }

            if ($ticket->technician && $ticket->technician->email) {
                $this->to[] = $ticket->technician->email;
            }

            $this->sendEmail();
        } else {
            if ($ticket->technician && $ticket->technician->email) {
                $this->to[] = $ticket->technician->email;
            }
        }
    }

    function sendEmail()
    {
        $ccEmails = array_merge($this->reply->cc ?? [], $this->cc ?? []);

        $toUsers = \App\User::whereIn('email', $this->to)->get()->pluck('email', 'email')->toArray();
        $ccUsers = \App\User::whereIn('email', $ccEmails)->get()->pluck('email', 'email')->toArray();

        if ($toUsers) {

            \Mail::to($toUsers)->cc($ccUsers)->send(new ReplyTicketMail($this->reply));
        }
        $this->sendSurvey($this->reply);
    }

    private function sendSurvey($reply)
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
