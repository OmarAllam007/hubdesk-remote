<?php

namespace App\Http\Resources;

use App\TicketLog;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketLogResource extends JsonResource
{

    public function toArray($request)
    {
        /** @var TicketLog $this */
        $data = [
            'id' => $this->id,
            'type' => $this->type,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'date_created' => $this->created_at->format('d/m/Y'),
            'type_action' => $this->type_action,
            'user' => $this->user->name,
            'description'=> $this->log_description
        ];

        if (in_array($this->type, $this->approval_logs)) {
            $data['approval_log_description'] = $this->approval_log_description;
        }
        if (!in_array($this->type,
                [TicketLog::AUTO_CLOSE, TicketLog::ESCALATION, TicketLog::REMINDER_ON_SURVEY]
            ) && !in_array($this->type, $this->approval_logs)) {
            $data['entries'] = collect($this->entries)->map(function ($entry){
                return [
                    'label'=> $entry->label,
                    'old_Value'=> $entry->old_value,
                    'new_value'=> $entry->new_value
                ];
            });
        }

        return $data;
    }


}
