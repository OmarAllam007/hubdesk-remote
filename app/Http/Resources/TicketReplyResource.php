<?php

namespace App\Http\Resources;

use App\TicketReply;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketReplyResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'user_id' => $this->user,
            'is_technician' => $this->ticket->technicain_id ? $this->ticket->technicain_id == $this->user->id : false,
            'content' => $this->content,
            'created_at' => $this->created_at->format('d/m/Y H:i A'),
            'class' => $this->class,
            'status' => $this->status->name,
            'status_id' => $this->status->id,
            'to' => $this->to ? implode(", ", $this->to) : [],
            'cc' => $this->cc ? implode(", ", $this->cc) : [],
            'attachments' => AttachmentsResource::collection($this->attachments),
        ];
    }
}
