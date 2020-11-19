<?php

namespace App\Providers;

use App\Attachment;
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

    public function boot()
    {
        Ticket::created(function (Ticket $ticket) {
//            dispatch(new ApplyBusinessRules($ticket));
            dispatch(new ApplySLA($ticket));

            if ($ticket->type == Ticket::TASK_TYPE) {
                Attachment::uploadFiles(Attachment::TASK_TYPE, $ticket->id);

                if ($ticket->technician) {
                    \Mail::send(new NewTaskMail($ticket));
                }

            } else {
                Attachment::uploadFiles(Attachment::TICKET_TYPE, $ticket->id);
            }

            dispatch(new ApprovalLevels($ticket));

            if ($ticket->category->business_service_type != 2 && (!$ticket->technician_id || !$ticket->group_id)) {
                $ticket->notify((new TicketNotAssignedNotification($ticket)));
            }
        });

        Ticket::updated(function (Ticket $ticket) {
            dispatch(new ApplySLA($ticket));
        });

        Ticket::updating(function (Ticket $ticket) {
            if (!$ticket->stopLog()) {
                TicketLog::addUpdating($ticket);
            }
        });

        Ticket::creating(function (Ticket $ticket) {
            $request = request();
            if (!$request->get('requester_id')) {
                $ticket->requester_id = $request->user()->id;
            }
            $ticket->location_id = $ticket->requester->location_id;
//            $ticket->business_unit_id = $ticket->requester->business_unit_id;
            $ticket->status_id = 1;
            $ticket->is_opened = 0;
            $ticket->creator_id = $request->user()->id;

        });

        Ticket::saving(function (Ticket $ticket) {
            $extract_image = new ExtractImages($ticket->description);
            $ticket->description = $extract_image->extract();
        });


        TicketApproval::created(function (TicketApproval $approval) {
            $approval->ticket->status_id = 6;
            TicketLog::approvalLog($approval, TicketLog::APPROVAL_TYPE);

            $approval->ticket->save();

            if ($approval->shouldSend()) {
                \Mail::to($approval->approver->email)->queue(new SendNewApproval($approval));
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
