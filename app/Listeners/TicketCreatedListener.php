<?php

namespace App\Listeners;

use App\Attachment;
use App\Jobs\ApplySLA;
use App\Jobs\ApprovalLevels;
use App\Jobs\NewTicketJob;
use App\Mail\NewTaskMail;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketNotAssignedNotification;
use App\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TicketCreatedListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($ticket)
    {
        dispatch(new ApplySLA($ticket));

        if ($ticket->type != Ticket::TASK_TYPE) {
            Attachment::uploadFiles(Attachment::TICKET_TYPE, $ticket->id);
        }

        dispatch(new NewTicketJob($ticket));
        dispatch(new ApprovalLevels($ticket));

    }
}
