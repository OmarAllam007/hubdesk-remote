<?php

namespace App\Mail;

use App\Jobs\ApplyBusinessRules;
use App\Ticket;
use App\User;
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
        $apply = new ApplyBusinessRules($this->task);
        $apply->fetchBusinessRule();
        $technician = $this->task->technician_id;
        if(request('technician')){
            $technician  = $this->task->technician_id = request('technician');
            $this->task->save();
        }

        $cc = ($apply->ccEmails && $apply->ccEmails->count()) ? $apply->ccEmails : [];
        $to = User::find($technician);

        return $this->markdown('emails.ticket.task_assigned', ['ticket' => $this->task])
            ->to($to)->cc($cc)
            ->subject('A new Task #' . $this->task->id . ' has been Assigned to you');
    }
}
