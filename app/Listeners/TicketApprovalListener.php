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

    public $cc = [];

    public function __construct()
    {
        $this->cc = [];
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

        if (in_array($ticketApproval->ticket->category_id, [161, 170, 171])) {
            $this->cc[] = 'saeed.ahmed@alkifah.com';
            $this->cc[] = 'eid@alkifah.com';
        }

        if (!$ticketApproval->ticket->isClosed() && !$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->status_id = 3;
            $ticketApproval->ticket->save();
        }

        if ($ticketApproval->ticket->technician_id) {
            // KGS Request FOR KRB 🤦🏻 🤦🏻 🤦🏻‍
            \Mail::to([$ticketApproval->ticket->technician->email, $ticketApproval->ticket->created_by->email])
                ->cc($this->cc)
                ->queue(new UpdateApprovalMail($ticketApproval));
        }

        if ($ticketApproval->status != -1 && !$ticketApproval->hasPendingOnSameStage() && $ticketApproval->hasNext()) {
            $approvals = $ticketApproval->getNextStageApprovals();
            foreach ($approvals as $approval) {
                \Mail::to($approval->approver->email)
//                    ->cc($this->cc)
                    ->queue(new SendNewApproval($approval));
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
