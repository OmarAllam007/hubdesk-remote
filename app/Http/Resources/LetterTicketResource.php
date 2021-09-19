<?php

namespace App\Http\Resources;

use App\LetterTicket;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterTicketResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var LetterTicket $this */
        return [
            'group' => $this->group->name,
            'subgroup' => $this->subgroup->name ?? 'Not Assigned',
            'letter_name' => $this->letter->name,
            'need_stamp' => $this->need_coc_stamp
        ];
    }
}
