<?php

namespace App\Notifications;

use App\Ticket;
use App\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReplyCreatedNotification extends Notification
{
    use Queueable;

    private $ticket;
    private $reply;

    /**
     * @param TicketReply $ticket
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['database'];
    }


    public function toArray($notifiable)
    {
        return [
            'ticket_id'=> $this->reply->ticket->id,
            'user_id'=> $this->reply->user_id,
        ];
    }
}
