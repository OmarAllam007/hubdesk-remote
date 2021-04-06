<?php

namespace App\Http\Resources;

use App\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{

    public function toArray($request)
    {
        /** @var Ticket $this */
        return [
            'id' => $this->id,
            'subject' => $this->subject ?? '',
            'category' => $this->category->name ?? '',
            'description' => $this->description ?? '',
            'status' => $this->status->name ?? '',
            'requester' => $this->requester->name ?? '',
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i') : '',
            'technician' => $this->technician->name ?? '',
            'technician_id' => $this->technician->id ?? '',
            'request_id' => $this->request_id ?? '',
            'authorization'=> $this->task_authorizations,
            'fields' => $this->custom_fields
        ];
    }
}
