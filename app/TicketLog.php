<?php

namespace App;

use App\Helpers\HistoryEntry;

/**
 * App\TicketLog
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ticket_id
 * @property integer $status_id
 * @property string $type
 * @property string $old_data
 * @property string $new_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereOldData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereNewData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TicketLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TicketLog extends KModel
{
    protected $fillable = ['user_id', 'type', 'old_data', 'new_data', 'status_id', 'ticket_id', 'created_at', 'updated_at'];

    const UPDATED_TYPE = 1;
    const REPLY_TYPE = 2;
    const APPROVAL_TYPE = 3;
    const APPROVED = 4;
    const DENIED = 5;
    const CLOSED_TYPE = 7;
    const AUTO_CLOSE = 10;
    const NOTE_TYPE = 11;
    const RESEND_APPROVAL = 12;
    const ESCALATION = 13;
    const SENT_TO_FINANCE = 14;
    const New_TASK = 15;
    const REMINDER_ON_SURVEY = 16;
    const DELETE_APPROVAL = 17;

    protected $casts = ['old_data' => 'array', 'new_data' => 'array'];

    public static function addReply(TicketReply $reply)
    {
        self::makeLog($reply->ticket, static::REPLY_TYPE, $reply->user_id);
    }

    public static function addNote(TicketNote $note)
    {
        self::makeLog($note->ticket, static::NOTE_TYPE, $note->user_id);
    }

    public static function addApproval(TicketApproval $approval)
    {
        return self::makeLog($approval->ticket, static::APPROVAL_TYPE, $approval->creator_id);
    }

    public static function approvalLog(TicketApproval $approval, $type)
    {
        $ticket = $approval->ticket;
        $user_id = \Auth::user()->id ?? $ticket->technician_id;

        return $approval->ticket->logs()->create([
            'user_id' => $user_id,
            'type' => $type,
            'old_data' => $ticket->getDirtyOriginals(),
            'new_data' => ['approval_id' => $approval->id],
            'status_id' => $ticket->status_id
        ]);
    }

    public static function addApprovalUpdate(TicketApproval $approval, $approved = true)
    {
        return self::makeLog($approval->ticket, $approved ? static::APPROVED : static::DENIED, $approval->approver_id);
    }

    public static function addEscalationLog(Ticket $ticket, EscalationLevel $escalation)
    {
        return self::makeLog($ticket, static::ESCALATION, $escalation->user_id);
    }

    public static function addUpdating(Ticket $ticket)
    {
        return self::makeLog($ticket, static::UPDATED_TYPE);
    }

    public static function addClose(Ticket $ticket)
    {
        return self::makeLog($ticket, static::CLOSED_TYPE);
    }

    public static function addAutoClose(Ticket $ticket)
    {
        return self::makeLog($ticket, static::AUTO_CLOSE, $ticket->requester_id);
    }

    public static function addNewTask(Ticket $ticket)
    {
        return self::makeLog($ticket->ticket, static::New_TASK, $ticket->requester_id);
    }

    public static function addReminderOnSurvey(Ticket $ticket)
    {
        return self::makeLog($ticket, static::REMINDER_ON_SURVEY, $ticket->requester_id);

    }

    public static function makeLog(Ticket $ticket, $type, $user_id = null)
    {
        $user_id = $user_id ?: \Auth::user()->id ?? $ticket->technician_id;
        return $ticket->logs()->create([
            'user_id' => $user_id,
            'type' => $type,
            'old_data' => $ticket->getDirtyOriginals(),
            'new_data' => $ticket->getDirty(),
            'status_id' => $ticket->status_id
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getTypeActionAttribute()
    {
        $actions = [
            self::REPLY_TYPE => 'replied',
            self::APPROVAL_TYPE => 'Ticket submitted for approval',
            self::APPROVED => 'Ticket approved',
            self::DENIED => 'denied',
            self::NOTE_TYPE => 'Updated by a note',
            self::RESEND_APPROVAL => 'Ticket updated by resend an approval ',
            self::SENT_TO_FINANCE => 'Ticket updated by sent to finance ',
            self::New_TASK => 'Updated, New Task has been created',
            self::REMINDER_ON_SURVEY => "Updated by Send Reminder On Pending Survey ",
            self::DELETE_APPROVAL => "Ticket Updated by delete approval"
        ];

        if (isset($actions[$this->type])) {
            if ($this->type == self::REPLY_TYPE && $this->status_id == 7) {
                return 'resolved';
            }
            return $actions[$this->type];
        }

        return 'updated';
    }

    public function getEntriesAttribute()
    {
        $entries = [];

        foreach ($this->old_data as $key => $value) {
            $entries[] = new HistoryEntry($key, $value, $this->new_data[$key]);
        }

        return $entries;
    }

    function getStartTimeAttribute()
    {
        $critical = $this->ticket->sla->critical ?? false;
        $date = clone $this->created_at;

        if (!$critical) {
            // If it is not critical and time is outside working hours
            // move the time to nearest working hour possible.
            $dayStart = (clone $this->created_at)->setTimeFromTimeString(config('worktime.start'));
            $dayEnd = (clone $this->created_at)->setTimeFromTimeString(config('worktime.end'));

            if ($date->lt($dayStart)) {
                // If it is before work start move to work start
                $date = $dayStart;
            } elseif ($date->gt($dayEnd)) {
                // If it is after working hours move to next day's start
                do {
                    $date = $dayStart->addDay();
                } while ($date->isWeekend());
            }
        }

        return $date;
    }

    function getColorTypeAttribute()
    {
        if (isset($this->new_data['status_id']) && in_array($this->new_data['status_id'], [7, 8, 9])) {
            return 'resolved-log';
        }
        if ($this->type == self::APPROVED) {
            return 'alert-success';
        } elseif (in_array($this->type,[self::DENIED,self::DELETE_APPROVAL])) {
            return 'alert-danger';
        }elseif (in_array($this->type, [self::REPLY_TYPE,self::UPDATED_TYPE])) {
            return 'alert-info';
        }elseif ($this->type == self::RESEND_APPROVAL) {
            return 'alert-warning';
        }

        return '';
    }


    function getApprovalLogsAttribute()
    {
        return [TicketLog::APPROVAL_TYPE, TicketLog::APPROVED, TicketLog::DENIED, TicketLog::DELETE_APPROVAL, TicketLog::RESEND_APPROVAL];
    }

    function getApprovalLogDescriptionAttribute()
    {
        if(!isset($this->new_data['approval_id']) && in_array($this->type,$this->approval_logs)){
            return $this->type_action;
        }

        $approval = TicketApproval::withTrashed()->find($this->new_data['approval_id']);

        if (!$approval) {// must not happened :)
            return;
        }

        $actions = [
            self::APPROVAL_TYPE => t("Approval Submitted to ") . $approval->approver->name . t(" by ") . $approval->created_by->name,
            self::RESEND_APPROVAL => t("Approval that has been sent to ") . $approval->approver->name . t(" has been resubmitted by ") . $approval->created_by->name,
            self::DELETE_APPROVAL => t("Approval that has been sent to ") . $approval->approver->name . t(" has been deleted") . t(" by ") . $approval->created_by->name,
            self::APPROVED => t("Approval that has been sent to ") . $approval->approver->name . t(" has been approved"),
            self::DENIED => t("Approval that has been sent to ") . $approval->approver->name . t(" has been denied"),
        ];

        return $actions[$this->type];

    }
}
