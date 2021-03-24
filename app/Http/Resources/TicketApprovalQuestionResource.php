<?php

namespace App\Http\Resources;

use App\ApprovalQuestion;
use App\Question;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketApprovalQuestionResource extends JsonResource
{
    /**
     * @param ApprovalQuestion $question
     * @return array
     */
    public function toArray($question)
    {
        return [
            'description' => $question->description,
            'color' => $question->color,
            'answer' => $question->answer_str ?? 'Not Assigned',

        ];
    }
}
