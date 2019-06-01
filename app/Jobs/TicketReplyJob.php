<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\TicketReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TicketReplyJob extends Job //implements ShouldQueue
{
    //use InteractsWithQueue, SerializesModels;

    /**
     * @var TicketReply
     */
    protected $reply;
    protected $to;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    public function handle()
    {
        $ticket = $this->reply->ticket;

        if ($this->reply->user_id == $this->reply->ticket->requester_id) {
            if(!$ticket->technician->email){
                return false;
            }
            $this->to = [$ticket->technician->email];

        } elseif ($this->reply->user_id == $this->reply->ticket->technician_id) {

            if(!$ticket->requester->email){
               return false;
            }
            $this->to = [$ticket->requester->email];

        } elseif ($this->reply->user_id != $this->reply->ticket->technician_id && $this->reply->user->isTechnician()) {
            if(!$ticket->requester->email || !$ticket->technician->email){
                return false;
            }

            $this->to = [$ticket->technician->email, $ticket->requester->email];


        }

        \Mail::send('emails.ticket.reply', ['reply' => $this->reply], function (Message $msg) {
            $ticket = $this->reply->ticket;
            $subject = 'Re: Ticket #' . $ticket->id . ' ' . $this->reply->ticket->subject;
            if ($this->reply->ticket->sdp_id) {
                $subject .= " [Request ##{$this->reply->ticket->sdp_id}##]";
            }
            $cc = request()->get("reply")["cc"] ?? null;
            $msg->subject($subject);
            $msg->to($this->to);
            if ($cc) {
                $msg->cc($cc);
            }
        });
    }

}
