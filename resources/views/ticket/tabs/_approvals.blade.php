<div>
    <approvals :ticket_id="{{$ticket->id}}"
               :is_task="{{$ticket->isTask() ? 1 : 0}}"
               :approvals="{{$ticket->isTask() ? $ticket->ticket->ticket_approvals : $ticket->ticket_approvals}}"
               :task_approvals="{{$ticket->isTask() ? $ticket->ticket_approvals : '{}' }}"
               :submit_approval="{{can('submit_approval',$ticket) ? 1 : 0}}"
               :templates="{{(auth()->user()->isSupport() && auth()->user()->reply_templates->count()) ?
                    auth()->user()->reply_templates
                       : '{}'}}"></approvals>
</div>
