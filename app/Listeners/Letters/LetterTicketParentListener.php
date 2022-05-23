<?php


namespace App\Listeners\Letters;


use App\Mail\LetterRequestForFees;
use App\Mail\NewTaskMail;
use App\Mail\ReplyTicketMail;
use App\Mail\SendSurveyEmail;
use App\Ticket;
use App\TicketLog;
use App\TicketReply;
use App\UserSurvey;
use Carbon\Carbon;

class LetterTicketParentListener
{
    protected function closeLetterTicket($letterTicket)
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


    protected function createKGSTask($letterTicket)
    {
        Ticket::flushEventListeners();
        TicketReply::flushEventListeners();
        $letterTicket->ticket->update([
            'status_id' => 4
        ]);


        $technician = config('letters.kgs_technician');
        $content = config('letters.fees_reply');

        if (in_array($letterTicket->ticket->requester->business_unit_id, [4, 39, 41, 42, 46])) {
            $technician = config('letters.kcc_technician');
            $content = '<p>On Hold</p>';
        }

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
            'technician_id' => $technician,
        ]);

        $reply = $letterTicket->ticket->replies()->create([
            'user_id' => config('letters.system_user'),
            'status_id' => 4,
            'content' => $content,
        ]);

        \Mail::send(new NewTaskMail($ticket));

        \Mail::to($letterTicket->ticket->requester->email)
            ->send(new LetterRequestForFees($letterTicket, $reply));
    }


    protected function sendEmail($letterTicket, $reply)
    {
        $requester = $letterTicket->ticket->requester->email;

        if ($letterTicket->ticket->requester->email) {
            \Mail::to($requester)->queue(new ReplyTicketMail($reply));

            $this->sendSurvey($letterTicket);
        }

    }

    protected function sendSurvey($letterTicket)
    {
        $survey = UserSurvey::create([
            'ticket_id' => $letterTicket->ticket->id,
            'survey_id' => $letterTicket->ticket->category->survey->first()->id,
            'comment' => '',
            'is_submitted' => 0,
            'notified' => 1
        ]);

        if ($survey->ticket->requester->email) {
            \Mail::queue(new SendSurveyEmail($survey));
        }
        TicketLog::addReminderOnSurvey($letterTicket->ticket);
    }
}