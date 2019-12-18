<ul class="list-group" style="box-shadow: 0px 2px 1px 0px lightgrey;">
<li class="list-group-item created-log">
    @if(!$ticket->isTask())
        <strong>{{t('Ticket created by')}}  {{$ticket->created_by->name}} {{t('at')}} {{$ticket->created_at->format('d/m/Y H:i')}}</strong>
    @else
        <strong>{{t('Task created by')}}  {{$ticket->created_by->name}} {{t('at')}} {{$ticket->created_at->format('d/m/Y H:i')}}</strong>
    @endif
</li>
</ul>
@foreach ($ticket->history as $date=>$logs)
    <div class="panel panel-default" style="box-shadow: 0px 2px 1px 0px lightgrey;">
        <!-- Default panel contents -->
        <div class="panel-heading" style="background-color: #e1e1e1">
           <strong>
               <a href="#log{{str_replace('-','',$date)}}" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                   {{$date}}
               </a>
           </strong>
        </div>

        <!-- List group -->
        <ul class="list-group collapse in" id="log{{str_replace('-','',$date)}}">
                @foreach($logs as $log)
            <li class="list-group-item {{$log->color_type}}">
                    @if ($log->type == \App\TicketLog::AUTO_CLOSE)
                        <strong>{{t('Ticket has been closed by the system')}} {{t('at')}} {{$log->created_at->format('d/m/Y H:i')}}</strong>
                    @elseif($log->type == \App\TicketLog::ESCALATION)
                        <strong>{{t('Ticket has been Escalated to')}} {{$log->user->name}}</strong>
                    @elseif($log->type == \App\TicketLog::REMINDER_ON_SURVEY)
                        <strong>{{t('Send email to submit the survey by the system')}} {{t('at')}} {{$log->created_at->format('d/m/Y H:i')}}</strong>
                    @elseif(in_array($log->type,$log->approval_logs))
                        <strong>
                            {{$log->approval_log_description}} {{t('at')}} {{$log->created_at->format('d/m/Y H:i')}}
                        </strong>
                    @else
                        <strong>{{t($ticket->isTask() ? 'Task '.$log->type_action.' by' :'Ticket '.$log->type_action.' by')}}   {{$log->user->name}}
                            {{t('at')}} {{$log->created_at->format('d/m/Y H:i')}}</strong>
                        <ul>
                            @foreach($log->entries as $entry)
                                <li>
                                    <small>{{ t($entry->label.' changed from') }}
                                        <strong>{{t($entry->old_value) ?: 'None'}}</strong>
                                        {{t('to')}} <strong>{{t($entry->new_value) ?: 'None'}}</strong></small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
            </li>
                @endforeach

        </ul>
    </div>
@endforeach
