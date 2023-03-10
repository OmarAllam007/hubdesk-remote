<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 10/11/17
 * Time: 8:16 AM
 */

namespace App\Observers;

use App\Attachment;
use App\ExtractImages;
use App\Helpers\ServiceDeskApi;
use App\Jobs\TicketReplyJob;
use App\Mail\AttachmentsReplyJob;
use App\Ticket;
use App\TicketLog;
use App\TicketReply;
use Carbon\Carbon;

class TicketReplyObserver
{
    public function creating(TicketReply $reply)
    {
        if ($reply->user_id == $reply->ticket->requester_id) {
            if ($reply->status_id) {
                $reply->ticket->status_id = $reply->status_id;
            } else {
                $reply->status_id = 1;
                $reply->ticket->status_id = 1;
            }
        }
        if (in_array($reply->user_id, [$reply->ticket->technician_id, $reply->user->isTechnicainSupervisor($reply->ticket)])) {
            $this->handleTechnician($reply);
        }

        if (can('show', $reply->ticket) && $reply->user_id != $reply->ticket->technician_id && !$reply->status_id) {
            $reply->status_id = $reply->ticket->status_id;
        }

        if ($reply->status_id == 8) {
            $reply->ticket->close_date = Carbon::now();
            if (!$reply->ticket->resolve_date) {
                $reply->ticket->resolve_date = Carbon::now();
            }
        }

        $extract_image = new ExtractImages($reply->content);
        $reply->content = $extract_image->extract();
        TicketLog::addReply($reply);
        $reply->ticket->save();
    }

    public function created(TicketReply $reply)
    {
        Attachment::uploadFiles(Attachment::TICKET_REPLY_TYPE, $reply->id);

        dispatch(new TicketReplyJob($reply));


        if ($reply->user_id == $reply->ticket->technician_id) {
            if ($reply->attachments->count()) {
                \Mail::queue(new AttachmentsReplyJob($reply->attachments));
            }
        }
    }

    protected function handleTechnician(TicketReply $reply)
    {

        if ($reply->status_id) {
            $reply->ticket->status_id = $reply->status_id;

            if ($reply->status_id == 7 || $reply->status_id == 9) {
                $reply->ticket->resolve_date = Carbon::now();
            }
        } else {
            $reply->status_id = $reply->ticket->status_id;
        }
    }
}