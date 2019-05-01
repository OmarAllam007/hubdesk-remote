<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 28/04/2019
 * Time: 2:09 PM
 */

namespace KGS\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFinanceMail extends Mailable
{

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
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

        $subject = "Hubdesk Finance - Request # {$this->data['ticket']->id}";
        return $this->markdown('kgs::emails.sent_to_finance',['ticket' => $this->data['ticket'],'content'=>$this->data['content']])->subject($subject);
    }

}