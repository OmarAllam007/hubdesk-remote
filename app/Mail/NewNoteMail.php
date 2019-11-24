<?php

namespace App\Mail;

use App\TicketNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $note;

    public function __construct(TicketNote $note)
    {
        $this->note = $note;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $note = $this->note;

        return $this->markdown('emails.ticket.new_note',['note'=>$note,'ticket'=>$note->ticket])
            ->subject('The ticket #' . $note->ticket->id.' updated with a new note')
            ->to($note->ticket->technician->email);
    }
}
