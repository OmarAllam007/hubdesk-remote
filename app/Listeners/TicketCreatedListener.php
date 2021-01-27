<?php

namespace App\Listeners;

use App\Attachment;
use App\Jobs\ApplySLA;
use App\Jobs\ApprovalLevels;
use App\Mail\NewTaskMail;
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


        dispatch(new ApprovalLevels($ticket));

        if ($ticket->category->business_service_type != 2 && (!$ticket->technician_id || !$ticket->group_id)) {
            $ticket->notify((new TicketNotAssignedNotification($ticket)));
        }
    }
}
