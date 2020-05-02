<?php

namespace App;

use App\Helpers\Ticket\TicketFilter;
use App\Helpers\Ticket\TicketViewScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * App\Ticket
 *
 * @property integer $id
 * @property integer $requester_id
 * @property integer $creator_id
 * @property integer $coordinator_id
 * @property integer $technician_id
 * @property integer $group_id
 * @property string $subject
 * @property string $description
 * @property integer $category_id
 * @property integer $subcategory_id
 * @property integer $item_id
 * @property integer $subitem_id
 * @property integer $status_id
 * @property integer $priority_id
 * @property integer $impact_id
 * @property integer $urgency_id
 * @property integer $sla_id
 * @property \Carbon\Carbon $due_date
 * @property \Carbon\Carbon $first_response_date
 * @property \Carbon\Carbon $resolve_date
 * @property \Carbon\Carbon $close_date
 * @property integer $business_unit_id
 * @property integer $location_id
 * @property integer $time_spent
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $requester
 * @property-read \App\User $technician
 * @property-read \App\Category $category
 * @property-read \App\Status $status
 * @property-read \App\Sla $sla
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereRequesterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereCreatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereCoordinatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereTechnicianId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereSubcategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket wherePriorityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereImpactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereUrgencyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereSlaId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereDueDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereFirstResponseDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereResolveDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereCloseDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereBusinessUnitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereLocationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereTimeSpent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticket extends KModel
{
    const TASK_TYPE = 2;
    protected $shouldApplySla = true;
    protected $stopLog = false;

    protected $fillable = [
        'subject', 'description', 'category_id', 'subcategory_id', 'item_id', 'group_id', 'technician_id',
        'priority_id', 'impact_id', 'urgency_id', 'requester_id', 'creator_id', 'status_id', 'sdp_id', 'type', 'request_id', 'is_opened', 'business_unit_id', 'subitem_id'
    ];

    protected $dates = ['created_at', 'updated_at', 'due_date', 'first_response_date', 'resolve_date', 'close_date'];

    /**
     * @var TicketReply
     */
    protected $resolution;

    /**
     * @var Collection
     */
    protected $attachments;

    protected $shouldApplyRules = true;

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function urgency()
    {
        return $this->belongsTo(Urgency::class);
    }

    public function impact()
    {
        return $this->belongsTo(Impact::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function subItem()
    {
        return $this->belongsTo(SubItem::class, 'subitem_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function sla()
    {
        return $this->belongsTo(Sla::class);
    }

    public function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function replies()
    {
        $relation = $this->hasMany(TicketReply::class);
        $relation->orderBy('id', 'desc');
        return $relation;
    }

    public function notes()
    {
        return $this->hasMany(TicketNote::class);
    }

    public function approvals()
    {
        $relation = $this->hasMany(TicketApproval::class);
        $relation->orderBy('stage');
        return $relation;
    }

    function fields()
    {
        return $this->hasMany(TicketField::class);
    }

    function getCustomFieldsAttribute()
    {
        return $this->type == Ticket::TASK_TYPE ? $this->ticket->fields->merge($this->fields) : $this->fields;
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function tasks()
    {
        return $this->hasMany(Ticket::class, 'request_id')
            ->where('type', 2)->where('request_id', $this->id);
    }

    public function user_survey()
    {
        return $this->hasOne(UserSurvey::class, 'ticket_id');
    }

    public function getTicketAttribute()
    {
        return Ticket::where('id', $this->request_id)->first();
    }

    public function getHistoryAttribute()
    {
        return TicketLog::select('*', \DB::raw('DATE(created_at) as date_created'))
            ->where('ticket_id', $this->id)->get()->groupBy('date_created');

    }

    public function getResolutionAttribute()
    {
        if (!$this->resolution) {
            $this->resolution = $this->replies()->where('status_id', 7)->first();
        }

        return $this->resolution;
    }

    public function hasPendingApprovals()
    {
        return $this->approvals()->where('status', TicketApproval::PENDING_APPROVAL)->count() > 0;
    }

    public function getDirtyOriginals()
    {
        if (!$this->isDirty()) {
            return [];
        }

        $attributes = [];
        $updated = array_keys($this->getDirty());

        foreach ($updated as $item) {
            $attributes[$item] = $this->getOriginal($item);
        }

        return $attributes;
    }

    public function stopLog($enable = null)
    {
        if (is_null($enable)) {
            return $this->stopLog;
        }

        $this->stopLog = $enable;

        return $this;
    }

    public function scopeScopedView(Builder $query, $scope)
    {
        $viewScope = new TicketViewScope($query);
        $viewScope->apply($scope);

        return $query;
    }

    public function scopeFilter(Builder $query, $criteria)
    {
        $filter = new TicketFilter($query, $criteria);
        $filter->apply();

        return $query;
    }

    public function scopeMonthScope(Builder $query, $filters)
    {
        if (empty($filters['filters'])) {
            return $query
                ->leftJoin('categories as cat', 'tickets.category_id', '=', 'cat.id')
                ->where('tickets.created_at', '>=', Carbon::now()->firstOfMonth()->toDateTimeString())
                ->where('tickets.created_at', '<=', Carbon::now()->lastOfMonth()->toDateTimeString())
                ->where('cat.business_unit_id', $filters['business_unit_id']);
        }
        return
            $query
                ->leftJoin('categories as cat', 'tickets.category_id', '=', 'cat.id')
                ->where('tickets.created_at', '>=', $filters['filters']['from'])
                ->where('tickets.created_at', '<=', $filters['filters']['to'])
                ->where('cat.business_unit_id', $filters['business_unit_id']);

    }

    public function isOpen()
    {
        return !$this->status || $this->status->type == Status::OPEN;
    }

    public function getFilesAttribute()
    {
        if (!$this->attachments) {
            $attachments = Attachment::where('type', Attachment::TICKET_TYPE)
                ->where('reference', $this->id)
                ->get();

            $replyAttachments = Attachment::where('type', Attachment::TICKET_REPLY_TYPE)
                ->whereIn('reference', $this->replies->pluck('id')->toArray())
                ->get();

            $approvalAttachments = Attachment::where('type', Attachment::TICKET_APPROVAL_TYPE)
                ->whereIn('reference', $this->approvals->pluck('id')->toArray())
                ->get();

            $ticketTasks = Attachment::whereIn('reference', $this->tasks->pluck('id'))
                ->where('type', Attachment::TASK_TYPE)
                ->orWhere('type', Attachment::TASK_TYPE)
                ->where('reference', $this->id)
                ->get();


            $this->attachments = $attachments->merge($replyAttachments);
            $this->attachments = $this->attachments->merge($approvalAttachments);
            $this->attachments = $this->attachments->merge($ticketTasks);
        }
        return $this->attachments;
    }

    public function hasApprovalStages()
    {
        if ($this->approvals->count() < 2) {
            return false;
        }

        return $this->approvals->pluck('stage', 'stage')->count() > 1;
    }

    public function approvalStages()
    {
        return $this->approvals->pluck('stage', 'stage');
    }

    public function nextApprovalStage()
    {
        if ($this->approvals->count()) {
            return $this->approvals()->max('stage') + 1;
        }

        return 1;
    }

    public function syncFields($fields)
    {
        $customFields = collect();
        foreach ($fields as $custom_field_id => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }

            $customFields->push(compact('custom_field_id', 'value'));
        }
        $this->fields()->createMany($customFields->toArray());
    }

    /*function fields()
    {
        return $this->hasMany(TicketCustomField::class);
    }*/


    function scopePending(Builder $query)
    {
        $query->whereHas('status', function (Builder $q) {
            $q->whereIn('type', [Status::OPEN, Status::PENDING]);
        });
    }

    function scopeOpen(Builder $query)
    {
        $query->whereHas('status', function (Builder $q) {
            $q->whereIn('type', [Status::OPEN]);
        });
    }

    function getStartTimeAttribute()
    {
        $critical = $this->sla->critical ?? false;
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

    /**
     * Filters only tickets that is connected to SDP
     * @param Builder $query
     */
    function scopeHasSdp(Builder $query)
    {
        $query->whereNotNull('sdp_id');
    }

    public function setApplySla($value)
    {
        $this->shouldApplySla = $value;

        return $this;
    }

    public function shouldApplySla()
    {
        return $this->shouldApplySla;
    }

    public function setApplyRules($value)
    {
        $this->shouldApplyRules = $value;

        return $this;
    }

    public function shouldApplyRules()
    {
        return $this->shouldApplyRules;
    }

    function last_updated_approval()
    {
        return $this->hasOne(TicketApproval::class)->whereIn('status', [1, -1, -2])->orderBy('approval_date', 'desc');
    }

    function calculateTime()
    {
        self::flushEventListeners();

        dispatch(new \App\Jobs\CalculateTicketTime($this));
    }

    function taskJson()
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject ?? '',
            'description' => $this->description ?? '',
            'status' => $this->status->name ?? '',
            'requester' => $this->requester->name ?? '',
            'created_at' => $this->created_at->format('d/m/Y H:i') ?? '',
            'technician' => $this->technician->name ?? '',
            'technician_id' => $this->technician->id ?? '',
            'request_id' => $this->request_id ?? '',
            'can_edit' => can('task_edit', $this),
            'can_show' => can('task_show', $this),
            'can_delete' => can('task_destroy', $this)
        ];
    }

    function isTask()
    {
        return $this->type == 2;
    }

    function isDuplicated()
    {
        return !$this->type && $this->request_id;
    }

    function hasDuplicatedTickets()
    {
        return Ticket::where('request_id', $this->id)->whereNull('type')->exists();
    }

    function getTypeNameAttribute()
    {
        if ($this->type == 2) {
            return 'Task';
        } elseif ($this->type == null && $this->request_id) {
            return 'Duplicated';
        } else {
            return 'Request';
        }
    }


    function getTypeIconAttribute()
    {
        if ($this->type == 2) {
            return 'tasks';

        } elseif ($this->type == null && $this->request_id) {
            return 'files-o';
        } else {
            return 'ticket';
        }
    }

    public function hasOpenTask()
    {
        return Ticket::where('type', 2)->where('request_id', $this->id)->whereNotIn('status_id', [7, 8, 9])->exists();
    }

    function shouldEscalate($escalation)
    {

        $previous_escalations = TicketLog::where('type', 13)
            ->where('ticket_id', $this->id)->count();

        if ($escalation->level > $previous_escalations) {

            $startTime = Carbon::parse(config('worktime.start'));
            $endTime = Carbon::parse(config('worktime.end'));
            $minutesPerDay = $endTime->diffInMinutes($startTime);

            $escalate_time = ($escalation->days * $minutesPerDay) + ($escalation->hours * 60) + $escalation->minutes;
            $escalation_time = $this->due_date->addMinutes($escalate_time * $escalation->when_escalate);

            /** @var Carbon $escalation_time */
            if (Carbon::now()->gte($escalation_time)) {
                return true;
            }

            return false;
        }

    }

    function isClosed()
    {
        return $this->status_id == 8;
    }


    function getTotalTicketCostAttribute()
    {
        $total_cost = 0;
        $total_cost += $this->total_service_cost ?? 0;
        foreach ($this->tasks as $task) {
            $total_cost += $task->total_service_cost;
        }

        $total_cost += $this->fees ? $this->fees->sum('cost') : 0;
        return $total_cost;
    }

    function getTotalServiceCostAttribute()
    {
        if ($this->item_id) {
            return $this->item->service_cost ?? 0;
        } elseif ($this->subcategory_id) {
            return $this->subcategory->service_cost ?? 0;
        }
        return $this->category->service_cost ?? 0;
    }

    function getSubjectLabelAttribute()
    {
        $label = $this->category->name;
        if ($this->subcategory) {
            $label .= ' >' . $this->subcategory->name;
        }
        if ($this->item) {
            $label .= ' >' . $this->item->name;
        }
        return $label;
    }


    function getFeesAttribute()
    {
        $fees_arr = [];
        $fees_arr[] = $this->category->fees ?? collect();
        $fees_arr[] = $this->subcategory->fees ?? collect();
        $fees_arr[] = $this->item->fees ?? collect();

        $fees = collect($fees_arr)->flatten();
        return $fees ?? collect();
    }

    function getSla($category, $subcategory = null, $item = null, $subItem = null)
    {
        $this->category_id = $category->id;

        if ($subcategory && $subcategory->sla) {
            $this->subcategory_id = $subcategory->id;
        }

        if ($item && $item->sla) {
            $this->item_id = $item->id;
        }
        $sla = new \App\Jobs\ApplySLA($this);
        $data = $sla->fetchSLA();

        return $data;
    }

    function getIsOpenedTicketAttribute()
    {
        return \Auth::user()->id == $this->technician_id && !$this->is_opened;
    }
}
