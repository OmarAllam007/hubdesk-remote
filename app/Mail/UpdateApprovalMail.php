<?php

namespace App\Mail;

use App\Ticket;
use App\TicketApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateApprovalMail extends Mailable
{
    use Queueable, SerializesModels;
    private $ticketApproval;
    private $ticket;

    /**
     * Create a new message instance.
     *
     * @param TicketApproval $ticketApproval
     */
    public function __construct(TicketApproval $ticketApproval)
    {
        $this->ticketApproval = $ticketApproval;
        $this->ticket = $ticketApproval->ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "The ticket #{$this->ticket->id} has been " . strtolower(TicketApproval::$statuses[$this->ticketApproval->status]) . " by " . $this->ticketApproval->approver->name;
        return $this->markdown('emails.ticket.approval-status', ['ticketApproval' => $this->ticketApproval])
            ->to([$this->ticket->technician->email, $this->ticketApproval->created_by->email])
            ->subject($subject);
    }
}
