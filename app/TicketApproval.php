<?php

namespace App;

use App\Jobs\SendApproval;


/**
 * App\TicketApproval
 *
 * @property integer $id
 * @property integer $creator_id
 * @property integer $approver_id
 * @property integer $ticket_id
 * @property string $comment
 * @property string $approval_date
 * @property string $status
 * @property Ticket $ticket
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer stage
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereCreatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereApproverId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereApprovalDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketApproval whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TicketApproval extends KModel
{
    protected $fillable = ['approver_id', 'content', 'status', 'comment', 'approval_date', 'stage', 'creator_id', 'hidden_comment',
        'ticket_id', 'created_at', 'updated_at'];

    protected $dates = ['created_at', 'updated_at', 'approval_date'];

    const APPROVED = 1;
    const PENDING_APPROVAL = 0;
    const DENIED = -1;
    const ESCALATED = -2;

    public static $statuses = [
        self::APPROVED => 'Approved',
        self::DENIED => 'Denied',
        self::PENDING_APPROVAL => 'Pending Approval',
        self::ESCALATED => 'Escalated',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    function questions()
    {
        return $this->hasMany(ApprovalQuestion::class, 'approval_id');
    }

    function getApprovalQuestionsStatusAttribute()
    {
        $status = self::APPROVED;

        if ($this->questions()->count()) {
            foreach ($this->questions as $key => $question) {
                if ($question->answer == self:: DENIED) {
                    $status = self::DENIED;
                }
            }
        }

        return $status;
    }

    function getStatusStrAttribute()
    {
        if ($this->status) {
            return self::$statuses[$this->status];
        }
    }


    public function escalate()
    {
        if ($this->status != static::PENDING_APPROVAL || !$this->shouldSend()) return false;

        $manager = $this->approver->manager;

        if ($manager) {
            $attributes = $this->getOriginal();
            unset($attributes['id'], $attributes['created_at'], $attributes['updated_at']);
            $attributes['approver_id'] = $manager->id;
            static::unguard(true);
            static::create($attributes);
            status::unguard(false);
            $this->update(['status' => static::ESCALATED]);
        } else {
            dispatch(new SendApproval($this));
        }

        return true;
    }

    public function shouldSend()
    {
        $pendingCount = $this->ticket->approvals()
            ->where('stage', '<', $this->stage ?? 0)
            ->where('status', static::PENDING_APPROVAL)
            ->count();

        return $pendingCount == 0;
    }

    public function hasNext()
    {
        return $this->ticket->approvals()->where('stage', '>', $this->stage)->count() > 0;
    }

    public function getNextStageApprovals()
    {
        $nextApprovals = $this->ticket->approvals()->where('stage', '>', $this->stage)->get();
        $approvals = collect();
        $previous = 0;

        /** @var TicketApproval $approval */
        foreach ($nextApprovals as $approval) {
            if ($previous && $approval->stage != $previous) {
                break;
            }

            $approvals->push($approval);
            $previous = $approval->stage;
        }

        return $approvals;
    }

    function getPendingAttribute()
    {
        return $this->status == static::PENDING_APPROVAL;
    }

    function getApprovalStatusAttribute()
    {
        return array_get(self::$statuses, $this->status);
    }

    function getResendAttribute()
    {
        $approvals = TicketLog::where('ticket_id', $this->ticket->id)->where('type', TicketLog::RESEND_APPROVAL)->get();
        $count = 0;
        foreach ($approvals as $approval) {
            if (isset($approval->new_data['approval_id']) && $this->id == $approval->new_data['approval_id']) {
                $count++;
            }
        }
        return $count;
    }

    function getActionDateAttribute()
    {
        if ($this->status != 0) {
            return $this->approval_date->format('d/m/Y h:i A');
        }
    }

    function getApprovalIconAttribute()
    {
        if ($this->status == self::APPROVED) {
            return 'check';
        } elseif ($this->status == self::DENIED) {
            return 'times';
        }
        return '';
    }

    function getApprovalColorAttribute()
    {
        if ($this->status == self::APPROVED) {
            return 'bg-green-200 ';
        } elseif ($this->status == self::DENIED) {
            return 'bg-red-200 ';
        }
        return '';
    }

    function getAttachmentsAttribute()
    {
        return Attachment::where('type', Attachment::TICKET_APPROVAL_TYPE)->where('reference', $this->id)->get();
    }

    function convertToJson()
    {
        return [
            'id' => $this->id,
            'color' => $this->approval_color,
            'icon' => $this->approval_icon,
            'approver' => $this->approver->name,
            'status' => $this->approval_status,
            'creator' => $this->created_by->name,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'stage' => $this->stage,
            'action_date' => $this->action_date,
            'resend' => $this->resend,
            'hidden_comment' => $this->hidden_comment,
            'comment' => $this->comment,
            'can_show' => $this->can_show(),
            'can_resend' => $this->can_resend(),
            'can_delete' => $this->can_delete()
        ];
    }

    function can_show()
    {
        return $this->pending && ($this->approver_id == auth()->id()) && !$this->ticket->isClosed();
    }

    function can_resend()
    {
        return $this->shouldSend() && $this->pending && auth()->id() == $this->creator_id;
    }

    function can_delete()
    {
        return $this->pending && (auth()->id() == $this->creator_id ||
            ($this->ticket->technician && auth()->id() == $this->ticket->technician->id));
    }
}
