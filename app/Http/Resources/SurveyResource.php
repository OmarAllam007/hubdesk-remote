<?php

namespace App\Http\Resources;

use App\UserSurvey;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{

    public function toArray($request)
    {

        /** @var UserSurvey $this */

        return [
            'id' => $this->id,
            'answers' => $this->survey_answers->map(function ($answer) {
                return [
                    'question' => $answer->answer->question->description,
                    'answer' => $answer->answer->description,
                    'degree' => number_format($answer->answer->degree),
                ];
            }),
            'is_submitted' => $this->is_submitted,
            'total_degree' => $this->total_score
        ];
    }
}
