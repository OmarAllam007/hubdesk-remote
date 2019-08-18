<?php

namespace App\Console\Commands;

use App\Jobs\NotifyTechnicainForNotViewedTicket;
use App\Ticket;
use Illuminate\Console\Command;

class CheckForNotOpenedTickets extends Command
{

    protected $signature = 'tickets:check-viewed-tickets';


    protected $description = 'Check for not viewed tickets';


    public function handle()
    {
        Ticket::where('is_opened',0)->get()->each(function ($ticket){
           dispatch(new NotifyTechnicainForNotViewedTicket($ticket));
        });
    }
}
