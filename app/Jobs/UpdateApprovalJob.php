<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\TicketApproval;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateApprovalJob extends Job
{

    /**
     * @var TicketApproval
     */
    private $ticketApproval;
    private $ticket;

    public function __construct(TicketApproval $ticketApproval)
    {
        $this->ticketApproval = $ticketApproval;
        $this->ticket = $ticketApproval->ticket;
    }

    public function handle()
    {
        \Mail::send('emails.ticket.approval-status', ['ticketApproval' => $this->ticketApproval], function (Message $msg) {
            $title = "The ticket #{$this->ticket->id} has been " . strtolower(TicketApproval::$statuses[$this->ticketApproval->status]) . " by " . $this->ticketApproval->approver->name;
            $msg->to([$this->ticket->technician->email]);
            $msg->subject($title);
        });
    }
}
