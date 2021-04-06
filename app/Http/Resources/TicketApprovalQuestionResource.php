<?php

namespace App\Http\Resources;

use App\ApprovalQuestion;
use App\Question;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketApprovalQuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'description' => $this->description,
            'color' => $this->color,
            'answer' => $this->answer_str ?? 'Not Assigned',
            'answer_id' => $this->answer
        ];
    }
}
