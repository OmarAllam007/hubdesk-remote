<?php

namespace App\Listeners\Letters;

use App\Attachment;
use App\Jobs\ApprovalLevels;
use App\Jobs\NewTicketJob;
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
        Attachment::uploadFiles(Attachment::TICKET_TYPE, $ticket->ticket_id);

        $business_unit_id = auth()->user()->business_unit_id;
        $appoval_levels = LetterApproval::where('business_unit_id', $business_unit_id)->get();

        dispatch(new NewTicketJob($ticket->ticket));

        $appoval_levels->each(function ($level) use ($ticket) {
            TicketApproval::create([
                'ticket_id' => $ticket->ticket_id,
                'creator_id' => config('letters.system_user'),
                'approver_id' => $level->user_id,
                'status' => 0,
                'stage' => $level->order,
                'content' => ''
            ]);
        });
    }
}
