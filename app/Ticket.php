<?php

namespace App;

use App\Behaviors\RequestConfiguration;
use App\Helpers\Ticket\TicketFilter;
use App\Helpers\Ticket\TicketViewScope;
use App\Http\Resources\TicketNoteResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
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
    use RequestConfiguration, Notifiable;

    const TASK_TYPE = 2;
    protected $shouldApplySla = true;
    protected $stopLog = false;

    protected $casts = [
        'client_info' => 'array'
    ];

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
            $ticketType = $this->isTask() ? Attachment::TASK_TYPE : Attachment::TICKET_TYPE;

            $attachments = Attachment::where('type', $ticketType)
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
//                ->orWhere('type', Attachment::TASK_TYPE)
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

    function getFeesAttribute()
    {
        $fees_arr = [];
        $fees_arr[] = $this->category->fees ?? collect();
        $fees_arr[] = $this->subcategory->fees ?? collect();
        $fees_arr[] = $this->item->fees ?? collect();

        $fees = collect($fees_arr)->flatten();
        return $fees ?? collect();
    }


    function getIsOpenedTicketAttribute()
    {
        return \Auth::user()->id == $this->technician_id && !$this->is_opened;
    }

    function getComplaintAttribute()
    {
        if ($this->item && $this->item->complaint) {
            return $this->item->complaint;
        }
        elseif ($this->subcategory && $this->subcategory->complaint) {
            return $this->subcategory->complaint;
        }
        elseif ($this->category->complaint) {
            return $this->category->complaint;
        }else {
            return [];
        }
    }

    function getTicketApprovalsAttribute()
    {
        return $this->approvals()->get()->map(function (TicketApproval $approval) {
            return $approval->convertToJson();
        });
    }

    function getTicketAuthorizationsAttribute()
    {
        return [
            'can_create_note' => \Auth::user()->isSupport() && !$this->isTask() ? 1 : 0,
            'can_resolve' => can('resolve', $this) ? 1 : 0,
            'submit_approval' => can('submit_approval', $this) ? 1 : 0,
            'reassign' => can('reassign', $this) ? 1 : 0,
            'forward' => can('forward', $this) ? 1 : 0,
            'task_edit' => can('task_edit', $this) ? 1 : 0,
            'send_to_finance' => can('send_to_finance', $this) ? 1 : 0,
            'send_complaint' => can('send_complaint', $this) ? 1 : 0,
            'display_ticket' => can('show', $this) ? 1 : 0,
            'is_support' => \Auth::user()->isSupport(),
            'can_convert_to_letter' => in_array(\Auth::user()->id, [1021, 1306, 1499302]) && $this->subcategory_id = 407
        ];
    }

    function getTaskAuthorizationsAttribute()
    {
        return [
            'can_edit' => can('task_edit', $this) ? 1 : 0,
            'can_show' => can('task_show', $this) ? 1 : 0,
            'can_delete' => can('task_destroy', $this) ? 1 : 0,
        ];
    }

    function convertToJson()
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
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d h:i') : 'Not Assigned',
            'due_date' => $this->due_date ? $this->due_date->format('Y-m-d h:i') : 'Not Assigned',
            'type' => $this->ticket_type ?? 'Not Assigned',
            'priority' => $this->priority->name ?? 'Not Assigned',
            'is_overdue' => $this->overdue ? 1 : 0,
            'resolution' => $this->resoultion,
            'has_submitted_survey' => $this->user_survey && $this->user_survey->is_submitted ? 1 : 0
        ];
    }

    function toJson($options = 0)
    {
        $ticket = $this->convertToJson();
        $ticket['item'] = $this->item ? t($this->item->name) : 'Not Assigned';
        $ticket['subItem'] = $this->subItem ? t($this->subItem->name) : 'Not Assigned';
        $ticket['group'] = $this->group->name ?? 'Not Assigned';
        $ticket['sla'] = $this->sla->name ?? t('Not Assigned');
        $ticket['description'] = $this->description;
        $ticket['service_cost'] = $this->total_service_cost ?? 'Not Assigned';
        $ticket['fields'] = $this->custom_fields;
        $ticket['notes'] = TicketNoteResource::collection($this->notes);
        $ticket['authorizations'] = $this->ticket_authorizations;

        return [
            'ticket' => $ticket,
            'requester' => $this->requester->toRequesterJson() ?? 'Not Assigned',
        ];
    }


    /**
     * Route notifications for the Slack channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return env('HUBDESK_ISSUES_SLACK');
    }


    function getIsLetterTicketAttribute()
    {
        return LetterTicket::where('ticket_id', $this->id)->exists();
    }

    function getLetterTicketAttribute()
    {
        if ($this->isTask() && $this->ticket->letter_ticket) {
            return LetterTicket::where('ticket_id', $this->ticket->id)->first();
        }

        return LetterTicket::where('ticket_id', $this->id)->first();
    }

    function getTicketServiceCustomFieldsAttribute(){
        $fields = collect();

        if ($this->category_id) {
            $category = $this->category;

            if ($category->custom_fields->count()) {
                $fields->push($category->custom_fields->sortBy('label')->groupBy('label'));
            }
        }
        if ($this->subcategory_id) {
            $subcategory = $this->subcategory;

            if ($subcategory && $subcategory->custom_fields->count()) {
                $fields->push($subcategory->custom_fields->sortBy('label')->groupBy('label'));
            }
        }
        if ($this->item_id) {
            $item = $this->item;
            if ($item && $item->custom_fields->count()) {
                $fields->push($item->custom_fields->sortBy('label')->groupBy('label'));
            }
        }

//        Get Fields if it task
        if(Ticket::TASK_TYPE == $this->type){

            $ticket = $this->ticket;

//            $fields = $this->ticket->custom_fields;

            $mainCategory = $ticket->category;

            $fields->push($mainCategory->custom_fields->sortBy('label')->groupBy('label'));

            if ($ticket->subcategory_id) {
                $subcategory = $ticket->subcategory;
                if ($subcategory && $subcategory->custom_fields->count()) {
                    $fields->push($subcategory->custom_fields->sortBy('label')->groupBy('label'));

                }
            }

            if ($ticket->item_id) {
                $item = $ticket->item;

                if ($item && $item->custom_fields->count()) {
                    $fields->push($item->custom_fields->sortBy('label')->groupBy('label'));
                }
            }
        }

        return $fields;
    }


}
