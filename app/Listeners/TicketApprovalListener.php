<?php

namespace App\Listeners;

use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Mail\SendNewApproval;
use App\Mail\TicketAssignedMail;
use App\Mail\UpdateApprovalMail;
use App\TicketApproval;
use App\TicketLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TicketApprovalListener
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
     * @param TicketApproval $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->ticket->item_id == config('letters.item_id')) {
            return;
        }

        $ticketApproval = $event;

        if (!$ticketApproval->ticket->isClosed() && !$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->status_id = 3;
            $ticketApproval->ticket->save();
        }

        if ($ticketApproval->ticket->technician_id) {
            \Mail::queue(new UpdateApprovalMail($ticketApproval));
        }

        if ($ticketApproval->status != -1 && !$ticketApproval->hasPendingOnSameStage() && $ticketApproval->hasNext()) {
            $approvals = $ticketApproval->getNextStageApprovals();
            foreach ($approvals as $approval) {
                \Mail::to($approval->approver->email)->queue(new SendNewApproval($approval));
            }
        }

        if ($ticketApproval->status == 1 && !$ticketApproval->hasNext() && !$ticketApproval->ticket->technician_id) {//KGS
            dispatch(new ApplyBusinessRules($ticketApproval->ticket));
            dispatch(new ApplySLA($ticketApproval->ticket));

            if ($ticketApproval->ticket->technician_id) {
                \Mail::queue(new TicketAssignedMail($ticketApproval->ticket));
            }
        }

        TicketLog::approvalLog($ticketApproval, $ticketApproval->status == TicketApproval::APPROVED ?
            TicketLog::APPROVED : TicketLog::DENIED);
    }
}
