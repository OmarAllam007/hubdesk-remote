<?php

namespace App\Notifications;

use App\TicketApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalUpdatedNotification extends Notification
{
    use Queueable;

    private $approval;

    public function __construct($approval)
    {
        //
        $this->approval = $approval;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'approver_id'=> $this->approval->approver_id,
            'ticket_id' => $this->approval->ticket_id,
            'creator_id' => $this->approval->creator_id,
            'approval_id' => $this->approval->id,
            'status' => $this->approval->status,
            'approval_date' => $this->approval->approval_date
        ];
    }
}
