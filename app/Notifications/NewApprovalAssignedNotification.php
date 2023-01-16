<?php

namespace App\Notifications;

use App\TicketApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApprovalAssignedNotification extends Notification
{
    use Queueable;

    /**
     * @var TicketApproval
     */
    private $approval;

    /**
     * Create a new notification instance.
     *
     * @param TicketApproval $approval
     */
    public function __construct($approval)
    {
        $this->approval = $approval;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->approval->ticket_id,
            'creator_id' => $this->approval->creator_id,
            'approval_id' => $this->approval->id
        ];
    }
}
