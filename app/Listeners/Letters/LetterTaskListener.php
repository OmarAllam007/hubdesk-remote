<?php

namespace App\Listeners\Letters;

use App\Mail\ReplyTicketMail;
use App\Ticket;
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
        if ($ticket->isTask() && $ticket->ticket->is_letter_ticket) {
            if (in_array($ticket->status_id, [7, 8, 9])) {
                Ticket::flushEventListeners();

                $ticket->ticket->update([
                    'status_id' => $ticket->status_id
                ]);

                $requester = $ticket->ticket->requester->email;

                if ($ticket->ticket->requester->email && request()->has('reply')) {
                    $lastReply = $ticket->ticket->replies()->last();
                    \Mail::to($requester)->send(new ReplyTicketMail($lastReply));

                    $this->sendSurvey($ticket->ticket);
                }
            }
        }
    }
}
