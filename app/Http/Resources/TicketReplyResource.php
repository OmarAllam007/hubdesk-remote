<?php

namespace App\Http\Resources;

use App\TicketReply;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketReplyResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    /**
     * @param TicketReply $reply
     * @return array
     */
    protected $reply;

    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    public function toArray($request)
    {

        return [
            'id' => $this->reply->id,
            'name' => $this->reply->user->name,
            'is_technician' => $this->reply->ticket->technicain_id ? $this->reply->ticket->technicain_id == $this->reply->user->id : false,
            'content' => $this->reply->content,
            'created_at' => $this->reply->created_at->format('d/m/Y H:i A'),
            'class' => $this->reply->class,
            'status' => $this->reply->status->name,
            'status_id' => $this->reply->status->id,
            'to' => $this->reply->to ? implode(", ", $this->reply->to) : [],
            'cc' => $this->reply->cc ? implode(", ", $this->reply->cc) : [],
            'attachments' => AttachmentsResource::collection($this->reply->attachments),
        ];
    }
}
