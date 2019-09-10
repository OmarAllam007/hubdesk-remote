<?php

namespace App\Jobs;

use App\Mail\SendNotificationOfNotViewedTicket;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyTechnicainForNotViewedTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;

        Carbon::setWeekendDays([Carbon::FRIDAY, Carbon::SATURDAY]);
    }


    public function handle()
    {

        $now = Carbon::now();

        if($this->ticket->technician && $now->diffInDays($this->ticket->created_at) >= 1){
            \Mail::to($this->ticket->technician->email)->send(new SendNotificationOfNotViewedTicket($this->ticket));
        }
    }
}
