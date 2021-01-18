<?php

namespace App\Http\Resources;

use App\TicketNote;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'creator' => $this->creator->name ?? 'Not assigned',
            'note' => $this->note,
            'can_display' => $this->display_to_requester || \Auth::user()->isSupport(),
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i A') : '',
            'ticket_id' => $this->ticket->id,
        ];
    }
}
