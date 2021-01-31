<?php

namespace App\Listeners;

use App\Attachment;
use App\Mail\NewTaskMail;
use App\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($ticket)
    {
        if ($ticket->type == Ticket::TASK_TYPE) {
            Attachment::uploadFiles(Attachment::TASK_TYPE, $ticket->id);

            if ($ticket->technician) {
                \Mail::send(new NewTaskMail($ticket));
            }
        }
    }
}
