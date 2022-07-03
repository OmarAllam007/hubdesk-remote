<?php

namespace App\Listeners\Letters;

use App\Attachment;
use App\Mail\ReplyTicketMail;
use App\Ticket;
use App\TicketReply;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LetterTaskListener
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
     * @param Ticket $ticket
     * @return void
     */
    public function handle($ticket)
    {
        if ($ticket->isTask() && isset($ticket->ticket) && $ticket->ticket->is_letter_ticket) {
            if (in_array($ticket->status_id, [7, 8, 9])) {
                Ticket::flushEventListeners();
                TicketReply::flushEventListeners();

                $ticket->ticket->update([
                    'status_id' => $ticket->status_id
                ]);

                $replyData = [
                    'user_id' => $ticket->technician_id,
                    'ticket_id' => $ticket->ticket->id,
                    'content' => request('reply.content'),
                    'status_id' => request('status_id'),
                    'created_at' => Carbon::now(),
                ];
//
                $reply = $ticket->ticket->replies()->create($replyData);
//
                Attachment::uploadFiles(Attachment::TICKET_REPLY_TYPE, $reply->id);

                $reply = new TicketReply($replyData);
                $requester = $ticket->ticket->requester->email;

                if ($ticket->ticket->requester->email && request()->has('reply')) {
                    \Mail::to($requester)->send(new ReplyTicketMail($reply));
                }
            }
        }
    }
}
