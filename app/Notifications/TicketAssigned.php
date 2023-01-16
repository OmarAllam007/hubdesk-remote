<?php

namespace App\Notifications;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssigned extends Notification implements ShouldQueue
{
    use Queueable;
    private $ticket;

    public function __construct($ticket)
    {

        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * @param Ticket $notifiable
     * @return string[]
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'requester_id' => $this->ticket->requester_id,
        ];
    }
}
