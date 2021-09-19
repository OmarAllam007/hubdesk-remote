<?php

namespace App\Jobs;

use App\Helpers\ChromePrint;
use App\LetterTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLetterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $letterTicket;

    /**
     * GenerateLetterJob constructor.
     * @param LetterTicket $letterTicket
     */
    public function __construct($letterTicket)
    {
        $this->letterTicket = $letterTicket;
    }


    public function handle()
    {
        $this->letterTicket->ticket->replies()->create([
            'user_id' => config('letters.system_user'),
            'status_id' => 8,
            'content'=> config('letters.close_reply_content'),
        ]);
    }
}
