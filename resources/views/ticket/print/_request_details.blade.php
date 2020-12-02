<div class="m-5 flex flex-col">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            <i class="fa fa-ticket"></i>
            @if(!$ticket->isTask())
                {{t('Request Details')}}
            @else
                {{t('Task Details')}}
            @endif
        </div>
        <div class="p-3">
            <table class="min-w-full table-auto">

                <tr>
                    <th class="text-left w-1/5">{{t('Category')}}</th>
                    <td class="text-left w-1/5">{{$ticket->category->name ?? 'Not Assigned'}}</td>
                    <th class="text-left w-1/5">{{t('Service Cost: ')}}</th>
                    <td class="text-left w-1/5">{{t($ticket->category->service_cost . ' SR')}}</td>
                </tr>
                <tr>
                    <th class="text-left w-1/5">{{t('Subcategory')}}</th>
                    <td class="text-left w-1/5">{{$ticket->subcategory->name ?? 'Not Assigned'}}</td>
                    <th class="text-left w-1/5">{{t('Technician')}}</th>
                    <td class="text-left w-1/5">{{$ticket->technician->name ?? 'Not Assigned'}}</td>
                </tr>
                <tr>
                    <th class="text-left w-1/5">{{t('Item')}}</th>
                    <td class="text-left w-1/5">{{$ticket->Item->name ?? 'Not Assigned'}}</td>
                    <th class="text-left w-1/5">{{t('First Response Due Time')}}</th>
                    <td class="text-left w-1/5">{{$ticket->first_response_date ?? 'Not Assigned'}}</td>


                </tr>
                <tr>
                    <th class="text-left w-1/5">{{t('Due Time')}}</th>
                    <td class="text-left w-1/5">{{$ticket->due_date ?? 'Not Assigned'}}</td>

                    <th class="text-left w-1/5">{{t('Urgency')}}</th>
                    <td class="text-left w-1/5">{{$ticket->urgency->name ?? 'Not Assigned'}}</td>
                </tr>
                <tr>
                    <th class="text-left w-1/5">{{t('SLA')}}</th>
                    <td class="text-left w-1/5">{{$ticket->sla->name ?? 'Not Assigned'}}</td>
                    <th class="text-left w-1/5">{{t('Group')}}</th>
                    <td class="text-left w-1/5">{{$ticket->group->name ?? 'Not Assigned'}}</td>


                </tr>
            </table>
        </div>
        <hr>
        <div class="pl-3 mt-2">
            <h4 class="panel-title"><i class="fa fa-asterisk"></i> <strong>
                    {{t('Additional Information')}}
                </strong>
            </h4>
        </div>
        <div class="p-3">
            <table class="min-w-full table-auto">
                <tbody>
                @if ($ticket->custom_fields->count())
                    @foreach($ticket->custom_fields as $field)
                        <tr class="w-full">
                            <td class="text-left w-1/2"><strong>{{$field->name}}</strong></td>
                            <td class="text-left w-1/2">
                                {{$field->value}}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>


</div>