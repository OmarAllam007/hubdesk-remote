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
            <td class="col-sm-3">{{$ticket->category->name or 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Service Cost: ')}}</th>
            <td class="col-sm-3">{{ $ticket->total_ticket_cost ? t($ticket->total_ticket_cost . ' SAR') : 'Not Assigned'}}</td>

        </tr> 
        <tr>
            <th class="col-sm-3">{{t('Subcategory')}}</th>
            <td class="col-sm-3">{{$ticket->subcategory->name or 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Technician')}}</th>
            <td class="col-sm-3">{{$ticket->technician->name or 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th class="col-sm-3">{{t('Item')}}</th>
            <td class="col-sm-3">{{$ticket->Item->name or 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('First Response Due Time')}}</th>
            <td class="col-sm-3">{{$ticket->first_response_date or 'Not Assigned'}}</td>


        </tr>
        <tr>
            <th class="col-sm-3">{{t('Due Time')}}</th>
            <td class="col-sm-3">{{$ticket->due_date or 'Not Assigned'}}</td>

            <th class="col-sm-3">{{t('Urgency')}}</th>
            <td class="col-sm-3">{{$ticket->urgency->name or 'Not Assigned'}}</td>
        </tr>
        <tr>
            <th class="col-sm-3">{{t('SLA')}}</th>
            <td class="col-sm-3">{{$ticket->sla->name or 'Not Assigned'}}</td>
            <th class="col-sm-3">{{t('Group')}}</th>
            <td class="col-sm-3">{{$ticket->group->name or 'Not Assigned'}}</td>
        </tr>
        {{--<tr>--}}
            {{--<th>{{t('Business Unit')}}</th>--}}
            {{--<td>{{$ticket->business_unit->name or 'Not Assigned'}}</td>--}}
            {{--<th>{{t('Location')}}</th>--}}
            {{--<td>{{$ticket->location->name or 'Not Assigned'}}</td>--}}
        {{--</tr>--}}
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

@include('ticket.partials._ticket_additional_fields',['ticket'=>$ticket])
@include('ticket.partials._requester_details',['ticket'=>$ticket])

@if($ticket->notes->count())
    <div class="panel panel-default panel-design">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-sticky-note-o"></i> {{t('Discussion Notes')}}</h4>
        </div>
        <table class="table table-striped table-condensed details-tbl">
            <thead>
            <tr>
                <th>{{t('Created By')}}</th>
                <th>{{t('Note')}}</th>
                <th>{{t('Created at')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ticket->notes as $note)
                <tr>
                    <td>{{$note->creator->name}}</td>
                    <td>@if($note->display_to_requester || Auth::user()->isSupport()) {!!$note->note !!} @else <b>private</b> @endif
                    </td>
                    <td>{{$note->created_at->format('d/m/Y H:i A') }}</td>
                    <td>
                        <button type="button" id="editNote" data-note="{{$note}}"
                                class="btn btn-primary btn-xs editNote" data-toggle="modal"
                                data-target="#ReplyModal">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" id="removeNote" data-note="{{$note}}"
                                class="btn btn-danger btn-xs removeNote" data-toggle="modal"
                                data-target="#removeNoteModal">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

@section('javascript')

@append