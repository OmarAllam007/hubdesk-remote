<?php

namespace App\Listeners;

use App\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InitCreatingTicketListener
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
     * @param $ticket
     * @return void
     */
    public function handle($ticket)
    {
        $request = request();
        if (!$request->get('requester_id')) {
            $ticket->requester_id = $request->user()->id;
        }
        $ticket->location_id = $ticket->requester->location_id;
//            $ticket->business_unit_id = $ticket->requester->business_unit_id;
        $ticket->status_id = 1;
        $ticket->is_opened = 0;
        $ticket->creator_id = $request->user()->id;
    }
}
