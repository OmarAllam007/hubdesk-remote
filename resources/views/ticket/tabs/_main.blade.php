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

@include('ticket.partials._ticket_additional_fields',['ticket'=>$ticket->type == App\Ticket::TASK_TYPE ? $ticket->ticket : $ticket])
@include('ticket.partials._requester_details',['ticket'=>$ticket])
@include('ticket.partials._notes',['ticket'=>$ticket])


@section('javascript')
<script>
    $('.editNote').on('click', function (e) {
        let modal = $('#ReplyModal');
        let note = $(this).data('note');
        modal.find('.modal-title').html('Edit Note - Ticket #' + note.ticket_id);
        modal.find('button[type=submit]').html('<i class="fa fa-save"></i> Save');
        let form = modal.closest('form').attr('action','note-edit/'+note.id);
        tinyMCE.activeEditor.setContent(note.note);
        $('#display_to_requester').attr('checked', note.display_to_requester==1 ? true : false);
        $('#email_to_technician').attr('checked', note.email_to_technician==1 ? true : false);
        $('#as_first_response').parent().hide();
    });

    $('.removeNote').on('click',function () {
        let modal = $('#removeNoteModal');
        let note = $(this).data('note');
        modal.find('.modal-title').html('Remove Note #' + note.id + '  - Ticket #'+note.ticket_id);
        modal.find('.modal-body').html('Are you sure to delete #' + note.id + ' note ?');
        let form = modal.closest('form').attr('action','remove-note/'+note.id);
    })
</script>
@append