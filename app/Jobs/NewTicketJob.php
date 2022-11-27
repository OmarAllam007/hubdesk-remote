<?php

namespace App\Jobs;

use App\BusinessRule;
use App\Jobs\Job;
use App\Mail\NewTicketCreated;
use App\Mail\TicketAssignedMail;
use App\Ticket;
use App\User;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTicketJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Ticket
     */
    private $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle()
    {

        if ($this->ticket->requester->email) {
            \Mail::send(new NewTicketCreated($this->ticket));
        }


        if ($this->ticket->technician) {
            \Mail::send(new TicketAssignedMail($this->ticket));
        }
    }
}
