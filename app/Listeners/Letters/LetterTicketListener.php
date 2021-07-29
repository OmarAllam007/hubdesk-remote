<?php

namespace App\Listeners\Letters;

use App\Jobs\ApprovalLevels;
use App\LetterApproval;
use App\LetterTicket;
use App\Ticket;
use App\TicketApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LetterTicketListener
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
     * @param LetterTicket $ticket
     * @return void
     */
    public function handle($ticket)
    {
        $business_unit_id = auth()->user()->business_unit_id;
        $appoval_levels = LetterApproval::where('business_unit_id', $business_unit_id)->get();

        $appoval_levels->each(function ($level) use ($ticket) {
            TicketApproval::create([
                'ticket_id' => $ticket->ticket_id,
                'creator_id' => $ticket->ticket->creator_id,
                'approver_id' => $level->user_id,
                'status' => 0,
                'stage' => $level->order,
                'content' => ''
            ]);
        });
    }
}
