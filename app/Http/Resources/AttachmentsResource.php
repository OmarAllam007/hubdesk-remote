<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at->format('d/m/Y H:i')
        ];
    }
}
