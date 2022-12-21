<?php

namespace App\Providers;

use App\Attachment;
use App\Events\TicketCreated;
use App\ExtractImages;
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Jobs\ApprovalLevels;
use App\Jobs\CalculateTicketTime;
use App\Jobs\NewTaskJob;
use App\Jobs\SendApproval;
use App\Mail\NewTaskMail;
use App\Mail\SendNewApproval;
use App\Notifications\TicketNotAssignedNotification;
use App\Ticket;
use App\TicketApproval;
use App\TicketLog;
use App\TicketNote;
use Illuminate\Support\ServiceProvider;

class TicketEventsProvider extends ServiceProvider
{
    public $cc;

    public function boot()
    {
//        Ticket::created(function (Ticket $ticket) {
////            dispatch(new ApplyBusinessRules($ticket));
////            dispatch(new ApplySLA($ticket));
////
////            if ($ticket->type == Ticket::TASK_TYPE) {
////                Attachment::uploadFiles(Attachment::TASK_TYPE, $ticket->id);
////
////                if ($ticket->technician) {
////                    \Mail::send(new NewTaskMail($ticket));
////                }
////
////            } else {
////                Attachment::uploadFiles(Attachment::TICKET_TYPE, $ticket->id);
////            }
////
////            dispatch(new ApprovalLevels($ticket));
////
////            if ($ticket->category->business_service_type != 2 && (!$ticket->technician_id || !$ticket->group_id)) {
////                $ticket->notify((new TicketNotAssignedNotification($ticket)));
////            }
//        });

        Ticket::updated(function (Ticket $ticket) {
            dispatch(new ApplySLA($ticket));
        });

        Ticket::updating(function (Ticket $ticket) {
            if (!$ticket->stopLog()) {
                TicketLog::addUpdating($ticket);
            }
        });

//        Ticket::creating(function (Ticket $ticket) {
//            \App\Services\TicketCreated::fire($ticket);
//        });

        Ticket::saving(function (Ticket $ticket) {
            $extract_image = new ExtractImages($ticket->description);
            $ticket->description = $extract_image->extract();
        });


        TicketApproval::created(function (TicketApproval $approval) {
            $approval->ticket->status_id = 6;
            TicketLog::approvalLog($approval, TicketLog::APPROVAL_TYPE);

            $approval->ticket->save();

            // KGS Request FOR KRB 🤦🏻 🤦🏻 🤦🏻‍️
            $this->cc = [];

            if (in_array($approval->ticket->category_id, [161, 170, 171])) {
                $this->cc[] = 'saeed.ahmed@alkifah.com';
                $this->cc[] = 'eid@alkifah.com';
            }

            if ($approval->shouldSend()) {
                \Mail::to($approval->approver->email)
                    ->cc($this->cc)
                    ->queue(new SendNewApproval($approval));
            }

//            Attachment::uploadFiles(Attachment::TICKET_APPROVAL_TYPE, $approval->id);
        });

        TicketApproval::updated(function (TicketApproval $approval) {
            if (!$approval->ticket->hasPendingApprovals()) {
//                TicketLog::addApprovalUpdate($approval, $approval->status == TicketApproval::APPROVED);

                $approval->ticket->save();
            }
        });

        TicketApproval::creating(function (TicketApproval $approval) {
            $extract_image = new ExtractImages($approval->content);
            $approval->content = $extract_image->extract();
        });
    }


    public function register()
    {
    }
}
