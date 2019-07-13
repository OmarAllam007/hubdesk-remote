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

            if(!$ticket->technician->email){
                return false;
            }
            $this->to = [$ticket->technician->email];
            $this->sendEmail();

        } elseif ($this->reply->user_id == $this->reply->ticket->technician_id) {

            if(!$ticket->requester->email){
               return false;
            }
            $this->to = [$ticket->requester->email];
            $this->sendEmail();

        } elseif ($this->reply->user_id != $this->reply->ticket->technician_id && $this->reply->user->isTechnician()) {
            if($ticket->requester->email){
                $this->to[] = $ticket->requester->email;
            }

            if ($ticket->technician->email){
                $this->to[] = $ticket->technician->email;
            }

            $this->sendEmail();
        }
    }

    function sendEmail(){
        \Mail::send('emails.ticket.reply', ['reply' => $this->reply], function (Message $msg) {
            $ticket = $this->reply->ticket;
            $subject = 'Re: Ticket #' . $ticket->id . ' ' . $this->reply->ticket->subject;
//            if ($this->reply->ticket->sdp_id) {
//                $subject .= " [Request ##{$this->reply->ticket->sdp_id}##]";
//            }
            $cc = request()->get("reply")["cc"] ?? null;
            $msg->subject($subject);
            $msg->to($this->to);
            if ($cc) {
                $msg->cc($cc);
            }
        });

        $ticket = $this->reply->ticket;
        if ($this->reply->status_id == 8 && $ticket->requester->email){
            $survey = UserSurvey::create([
                'ticket_id' => $ticket->id,
                'survey_id' => $ticket->category->survey->first()->id,
                'comment' => '',
                'is_submitted' => 0,
                'notified' => 1
            ]);

            \Mail::send(new SendSurveyEmail($survey));
            TicketLog::addReminderOnSurvey($ticket);
        }
    }

}
