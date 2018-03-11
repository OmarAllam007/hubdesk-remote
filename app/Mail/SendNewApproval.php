<?php

namespace App\Mail;

use App\TicketApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $approval;
    public function __construct(TicketApproval $approval)
    {
        $this->approval = $approval;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->approval->approver->name;
        $link = link_to_route('approval.show', null, $this->approval);
        $content = str_replace(['$approver', '$approvalLink'], [$name, $link], $this->approval->content);
        if(!$this->approval->ticket->sdp_id){
            $subject = 'Approval required for request #' . $this->approval->ticket_id;
        }
        else{
            $subject = "Approval required for [Request ##{$this->approval->ticket->sdp_id}##]";
        }
        return $this->markdown('emails.ticket.request-for-approval',['approval' => $this->approval, 'content' => $content])->subject($subject);
    }
}
