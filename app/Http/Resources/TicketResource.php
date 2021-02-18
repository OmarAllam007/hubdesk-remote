<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{

    public function toArray($request)
    {
        $ticket = [
            'id' => $this->id ?? '',
            'subject' => $this->subject ?? '',
            'requester' => $this->requester->name ?? 'Not Assigned',
            'employee_id' => $this->requester ? $this->requester->employee_id : 'Not Assigned',
            'technician' => $this->technician ? $this->technician->name : 'Not Assigned',
            'status' => t($this->status->name ?? 'Not Assigned'),
            'status_id' => $this->status_id ?? 'Not Assigned',
            'category' => t($this->category->name ?? 'Not Assigned'),
            'subcategory' => $this->subcategory ? t($this->subcategory->name) : 'Not Assigned',
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d h:i') : 'Not Assigned',
            'due_date' => $this->due_date ? $this->due_date->format('Y-m-d h:i') : 'Not Assigned',
            'type' => $this->ticket_type ?? 'Not Assigned',
            'priority' => $this->priority->name ?? 'Not Assigned',
            'is_overdue' => $this->overdue ? 1 : 0,
        ];

        $ticket['item'] = $this->item ? t($this->item->name) : 'Not Assigned';
        $ticket['subItem'] = $this->subItem ? t($this->subItem->name) : 'Not Assigned';
        $ticket['group'] = $this->group->name ?? 'Not Assigned';
        $ticket['sla'] = $this->sla->name ?? t('Not Assigned');
        $ticket['description'] = $this->description;
        $ticket['service_cost'] = $this->total_service_cost ?? 'Not Assigned';
        $ticket['fields'] = $this->custom_fields;
        $ticket['notes'] = TicketNoteResource::collection($this->notes);
        $ticket['authorizations'] = $this->ticket_authorizations;
        $ticket['resolution'] = TicketReplyResource::make($this->resolution);

        return [
            'ticket' => $ticket,
            'requester' => $this->requester->toRequesterJson() ?? 'Not Assigned',
        ];
    }

}
