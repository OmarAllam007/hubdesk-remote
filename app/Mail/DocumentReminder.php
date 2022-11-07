<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use KGS\Document;

class DocumentReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $document;
    /**
     * @var Ticket
     */
    private $ticket;

    public function __construct(Document $document, Ticket $ticket)
    {
        $this->document = $document;
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "New Ticket for renew - {$this->document->name}";
        return $this->markdown('kgs::emails.renew_document', [
            'document' => $this->document,
            'ticket' => $this->ticket
        ])->subject($subject);
    }
}
