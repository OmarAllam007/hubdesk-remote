<?php

namespace App\Http\Resources;

use App\CustomField;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketFieldResource extends JsonResource
{

    public function toArray($request)
    {
        /** @var CustomField $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'options' => json_decode($this->options),
            'type' => $this->type,
            'level' => $this->level,
            'level_id' => $this->level_id,
        ];
    }
}
