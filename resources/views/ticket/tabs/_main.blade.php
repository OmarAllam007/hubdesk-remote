<div class="panel panel-default ticket-description">
    <div class="panel-body ">
        {!! tidy_repair_string($ticket->description, [], 'utf8') !!}
    </div>
</div>

<div class="panel panel-default panel-design">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-ticket"></i>
            @if(!$ticket->isTask())
                {{t('Request Details')}}
            @else
                {{t('Task Details')}}
            @endif
        </h4>
    </div>
    <table class="table table-striped table-condensed details-tbl">
        <tr>
            <th class="col-sm-3">{{t('Category')}}</th>
            <td class="col-sm-3">{{$ticket->category->name ?? 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Group')}}</th>
            <td class="col-sm-3">{{t($ticket->group->name ?? 'Not Assigned') }}</td>


        </tr>
        <tr>
            <th class="col-sm-3">{{t('Subcategory')}}</th>
            <td class="col-sm-3">{{$ticket->subcategory->name ?? 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Technician')}}</th>
            <td class="col-sm-3">{{$ticket->technician->name ?? 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th class="col-sm-3">{{t('Item')}}</th>
            <td class="col-sm-3">{{$ticket->Item->name ?? 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Priority')}}</th>
            <td class="col-sm-3">{{$ticket->priority->name ?? 'Not Assigned'}}</td>


        </tr>
        <tr>
            <th class="col-sm-3">{{t('SubItem')}}</th>
            <td class="col-sm-3">{{$ticket->subItem->name ?? 'Not Assigned'}}</td>

            <th class="col-sm-3">{{t('Service Cost: ')}}</th>
            <td class="col-sm-3">{{ $ticket->total_ticket_cost ? t($ticket->total_ticket_cost . ' SAR') : 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th class="col-sm-3">{{t('SLA')}}</th>
            <td class="col-sm-3">{{$ticket->sla->name ?? 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Due Time')}}</th>
            <td class="col-sm-3">{{$ticket->due_date ?? 'Not Assigned'}}</td>

        </tr>
        {{--        // KGS_ONLY--}}
        @if(env('GS_ID') && $ticket->category->business_unit_id == env('GS_ID'))
            <tr>
                <th class="col-sm-3">{{t('Business Unit')}}</th>
                <td class="col-sm-3">{{$ticket->business_unit->name ?? 'Not Assigned'}}</td>
                <th class="col-sm-3">{{t('Service Type')}}</th>
                <td class="col-sm-3">{{t($ticket->category->business_service_type_str)}}</td>
            </tr>
        @endif
    </table>
</div>

{{--{{dd($ticket->fees)}}--}}
@if ($ticket->fees->count())
    <div class="panel panel-default panel-design">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-asterisk"></i> {{t('Additional Fees')}}</h4>
        </div>

        <table class="table table-bordered table-condensed table-striped details-tbl">
            <tbody>
            @foreach($ticket->fees as $fee)
                <tr>
                    <td class="col-sm-4 text-right"><strong>{{$fee->name}}</strong></td>
                    <td>
                        {{$fee->cost}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

@include('ticket.partials._ticket_additional_fields',['ticket'=> $ticket])
@include('ticket.partials._requester_details',['ticket'=>$ticket])
@include('ticket.partials._notes',['ticket'=>$ticket])
