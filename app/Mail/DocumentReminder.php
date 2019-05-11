<?php

namespace App\Mail;

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
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        $name = $this->document->approver->name;
//        $link = link_to_route('kgs.document.index', null, $this->document);
//        $content = str_replace(['$approver', '$approvalLink'], [$name, $link], $this->document->content);

        $subject = "Reminder on renew document - {$this->document->name}";
        return $this->markdown('kgs::emails.renew_document',['document' => $this->document])->subject($subject);
    }
}
