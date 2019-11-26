<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Ticket $task)
    {
        $this->task = $task;
    }


    public function build()
    {
        return $this->markdown('emails.ticket.task_assigned', ['ticket' => $this->task])
            ->to($this->task->technician->email)
            ->subject('A new Task #' . $this->task->id .' has been Assigned to you');
    }
}
