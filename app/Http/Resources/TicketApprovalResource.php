<?php

namespace App\Http\Resources;

use App\TicketApproval;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketApprovalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param TicketApproval $approval
     *
     */
    protected $approval;

    public function __construct($approval)
    {
        $this->approval = $approval;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->approval->id,
            'created_by' => $this->approval->created_by->name ?? 'Not Assigned',
            'created_at' => $this->approval->created_at->format('d/m/Y H:i A'),
            'approver' => $this->approval->approver->name ?? 'Not assigned',
            'content' => $this->approval->content,
            'status' => t(\App\TicketApproval::$statuses[$this->approval->status]),
            'status_id' => $this->approval->status,
            'attachments' => AttachmentsResource::collection($this->approval->attachments),
            'questions' => TicketApprovalQuestionResource::collection($this->approval->questions),
        ];
    }
}
