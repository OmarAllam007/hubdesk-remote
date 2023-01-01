<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DaamTicketCreatedListener
{

    public function __construct()
    {

    }

    public function handle($ticket)
    {
        if ($ticket->category->business_unit_id == 59) {//daam company
            $ticket->update([
                'group_id' => 138
            ]);
        }
    }
}
