<?php

namespace App\Listeners;

use App\Notifications\TicketNotAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TicketNotAssignedListener
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
        if ($ticket->category->business_service_type != 2 && (!$ticket->technician_id || !$ticket->group_id)) {
            $ticket->notify((new TicketNotAssignedNotification($ticket)));
        }
    }
}
