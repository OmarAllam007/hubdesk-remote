<?php

namespace App\Listeners\Letters;

use App\Jobs\CloseLetterTicketJob;
use App\Jobs\GenerateLetterJob;
use App\Jobs\TicketReplyJob;
use App\LetterTicket;
use App\Mail\NewTaskMail;
use App\Mail\ReplyTicketMail;
use App\Mail\SendSurveyEmail;
use App\Ticket;
use App\TicketApproval;
use App\TicketLog;
use App\TicketReply;
use App\UserSurvey;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LetterTicketApprovalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(TicketApproval $event)
    {
        //check if it is letter services
        if ($event->ticket->item_id == config('letters.item_id')) {
            if ($event->status == TicketApproval::DENIED) {
                $event->ticket->update([
                    'status_id' => 9,
                ]);

                $event->ticket->replies()->create([
                    'content' => 'Ticket has been closed as the approval denied',
                ]);

            } else {
                $letterTicket = LetterTicket::where('ticket_id', $event->ticket->id)->first();

                if ($letterTicket->need_coc_stamp) {
                    $this->createKGSTask($letterTicket);
                } else {
                    $this->closeLetterTicket($letterTicket);
                }
            }
        }
    }

    private function closeLetterTicket($letterTicket)
    {
        Ticket::flushEventListeners();
        TicketReply::flushEventListeners();
        TicketLog::flushEventListeners();

        $letterTicket->ticket->update([
            'status_id' => 8,
            'closed_date' => Carbon::now()->toDateTimeString()
        ]);

        $reply = $letterTicket->ticket->replies()->create([
            'user_id' => config('letters.system_user'),
            'status_id' => 8,
            'content' => config('letters.close_reply_content'),
        ]);

        TicketLog::addReply($reply);
        TicketLog::makeLog($letterTicket->ticket, TicketLog::UPDATED_TYPE, config('letters.system_user'));

        $this->sendEmail($letterTicket, $reply);
    }

    private function createKGSTask($letterTicket)
    {
        Ticket::flushEventListeners();

        $letterTicket->ticket->update([
            'status_id' => 4
        ]);

        $ticket = Ticket::create([
            'subject' => $letterTicket->ticket->subject,
            'description' => $letterTicket->ticket->subject,
            'type' => Ticket::TASK_TYPE,
            'request_id' => $letterTicket->ticket->id,
            'requester_id' => config('letters.system_user'),
            'creator_id' => config('letters.system_user'),
            'status_id' => 1,
            'category_id' => $letterTicket->ticket->category_id,
            'subcategory_id' => $letterTicket->ticket->subcategory_id,
            'item_id' => $letterTicket->ticket->item_id,
            'group_id' => config('letters.kgs_group'),
            'technician_id' => config('letters.kgs_technician'),
        ]);

        \Mail::send(new NewTaskMail($ticket));
    }

    private function sendEmail($letterTicket, $reply)
    {
        $requester = $letterTicket->ticket->requester->email;

        if ($letterTicket->ticket->requester->email) {
            \Mail::to($requester)->send(new ReplyTicketMail($reply));

            $this->sendSurvey($letterTicket);
        }

    }

    private function sendSurvey($letterTicket)
    {
        $survey = UserSurvey::create([
            'ticket_id' => $letterTicket->ticket->id,
            'survey_id' => $letterTicket->ticket->category->survey->first()->id,
            'comment' => '',
            'is_submitted' => 0,
            'notified' => 1
        ]);

        if ($survey->ticket->requester->email) {
            \Mail::send(new SendSurveyEmail($survey));
        }
        TicketLog::addReminderOnSurvey($letterTicket->ticket);
    }


}
