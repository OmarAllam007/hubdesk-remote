<?php

namespace App\Listeners\Letters;

use App\Jobs\CloseLetterTicketJob;
use App\Jobs\GenerateLetterJob;
use App\Jobs\TicketReplyJob;
use App\LetterTicket;
use App\Mail\LetterRequestForFees;
use App\Mail\NewTaskMail;
use App\Mail\ReplyTicketMail;
use App\Mail\SendSurveyEmail;
use App\Mail\TicketReplyMail;
use App\Ticket;
use App\TicketApproval;
use App\TicketLog;
use App\TicketReply;
use App\UserSurvey;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LetterTicketApprovalListener extends LetterTicketParentListener
{

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


}
