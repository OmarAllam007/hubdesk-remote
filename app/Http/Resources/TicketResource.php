<?php

namespace App\Http\Resources;

use App\LetterTicket;
use App\Ticket;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class TicketResource extends JsonResource
{

    private $fields = [];

    public function toArray($request)
    {
        /** @var Ticket $this */
        $ticket = $this->getMainTicketDetails();

        $this->files = $this->getFilesOfTicket();

        $letterTicket = LetterTicket::where('ticket_id', $this->request_id ? $this->ticket->id : $this->id)->first();

        $keyedFields = $this->ticket_service_custom_fields->flatten()->keyBy('name');

        $this->custom_fields->map(function ($field, $index) use ($keyedFields) {
            if (str_contains(strtolower($field->name), 'date') &&
                \DateTime::createFromFormat('Y-m-d h:i a', $field->value) !== false) {
                $fieldValue = Carbon::parse($field->value)->format('Y-m-d h:i a');

            } else {
                $fieldValue = $field->value;
            }

            $this->fields[$index]['name'] = $field->name;
            $this->fields[$index]['value'] = $fieldValue;

            if ($keyedFields->count() && $keyedFields->get($field['name']) !== null) {
                $this->fields[$index]['order'] = $keyedFields->get($field['name'])->order;
            }
        });

        $this->fields = collect($this->fields)->sortBy('order');
        $ticket['item'] = $this->item ? t($this->item->name) : 'Not Assigned';
        $ticket['subItem'] = $this->subItem ? t($this->subItem->name) : 'Not Assigned';
        $ticket['group'] = $this->group->name ?? 'Not Assigned';
        $ticket['sla'] = $this->sla->name ?? t('Not Assigned');
        $ticket['description'] = $this->description;
        $ticket['service_cost'] = $this->total_service_cost ?? 'Not Assigned';
        $ticket['fields'] = $this->fields->toArray();
        $ticket['notes'] = TicketNoteResource::collection($this->notes);
        $ticket['authorizations'] = $this->ticket_authorizations;
        $ticket['resolution'] = TicketReplyResource::make($this->resolution);
        $ticket['approvals'] = $this->isTask() ? TicketApprovalResource::collection($this->ticket->approvals()->latest()->get())
            : TicketApprovalResource::collection($this->approvals()->latest()->get());
        $ticket['task_approvals'] = $this->isTask() ? $this->ticket_approvals : [];
        $ticket['attachments'] = AttachmentsResource::collection($this->files);
        $ticket['is_completed_approved_ticket'] = LetterTicket::isCompletedTicket($this) || $this->item_id == 445;
        $ticket['is_kgs_letter_ticket'] = LetterTicket::isKGSLetterTicket($this);
        $ticket['is_approved_letter_ticket'] = LetterTicket::isApprovedTicket($this) || $this->item_id == 445;
        $ticket['letter_ticket'] = $letterTicket ? LetterTicketResource::make($letterTicket) : null;

        return [
            'ticket' => $ticket,
            'requester' => $this->requester->toRequesterJson() ?? 'Not Assigned',
        ];
    }

    function getFilesOfTicket()
    {
        $files = $this->files;

        if ($this->is_letter_ticket) {
            $this->tasks->each(function ($task) use ($files) {
                $task->files->each(function ($file) use ($files) {
                    $files->push($file);
                });
            });
        }
        return $files;
    }


    function getMainTicketDetails()
    {
        return [
            'id' => $this->id ?? '',
            'subject' => $this->subject ?? '',
            'requester' => $this->requester->name ?? 'Not Assigned',
            'employee_id' => $this->requester ? $this->requester->employee_id : 'Not Assigned',
            'technician' => $this->technician ? $this->technician->name : 'Not Assigned',
            'status' => t($this->status->name ?? 'Not Assigned'),
            'status_id' => $this->status_id ?? 'Not Assigned',
            'category' => t($this->category->name ?? 'Not Assigned'),
            'subcategory' => $this->subcategory ? t($this->subcategory->name) : 'Not Assigned',
            'created_at' => $this->created_at ? $this->created_at->format('Y/m/d h:i') : 'Not Assigned',
            'due_date' => $this->due_date ? $this->due_date->format('Y/m/d h:i') : 'Not Assigned',
            'close_date' => $this->close_date ? $this->close_date->format('Y/m/d h:i') : '',
            'type' => $this->ticket_type ?? 'Not Assigned',
            'priority' => $this->priority->name ?? 'Not Assigned',
            'is_overdue' => $this->overdue ? 1 : 0,
            'is_task' => $this->isTask() ? 1 : 0,
            'category_id' => $this->category_id ?? '',
            'subcategory_id' => $this->subcategory_id ?? '',
            'item_id' => $this->item_id ?? '',
            'subitem_id' => $this->subitem_id ?? '',
            'technician_id' => $this->technician_id ?? '',
            'group_id' => $this->group_id ?? '',
            'request_id' => $this->request_id ?? '',
            'is_duplicated' => $this->isDuplicated(),
            'is_support' => \Auth::user()->isSupport(),
            'business_unit' => $this->business_unit->name ?? 'Not Assigned',
            'survey' => $this->user_survey ? SurveyResource::make($this->user_survey)->toArray(null) : []
        ];
    }

}
