<?php

namespace App\Jobs;

use App\Ticket;
use App\TicketLog;
use App\TicketReply;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloseLetterTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $letterTicket;

    public function __construct($letterTicket)
    {
        //
        $this->letterTicket = $letterTicket;
    }

    public function handle()
    {
//        TicketLog::flushEventListeners();
//        TicketReply::flushEventListeners();
//        Ticket::flushEventListeners();
//
//        $this->letterTicket->ticket->update([
//            'status_id' => 8,
//            'closed_date' => Carbon::now()->toDateTimeString()
//        ]);
//
//        $reply = $this->letterTicket->ticket->replies()->create([
//            'user_id' => config('letters.system_user'),
//            'status_id' => 8,
//            'content' => config('letters.close_reply_content'),
//        ]);
//
//        TicketLog::addReply($reply);
//        TicketLog::makeLog($this->letterTicket->ticket, TicketLog::UPDATED_TYPE, config('letters.system_user'));
    }
}
