@extends('layouts.app')

@section('header')

    <h2>{{ $report->title }}</h2>
    <a href=""></a>
    <div class="btn-toolbar">
        <a href="?excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> {{ t('Excel') }}</a>
{{--        <a href="?pdf" class="btn btn-success btn-sm"><i class="fa fa-file"></i> {{ t('PDF') }}</a>--}}
        <a href="{{route('reports.index')}}" class="btn btn-default btn-sm"><i
                    class="fa fa-chevron-left"></i> {{ t('Back') }}
        </a>
    </div>

    <style>
        /*.search-table-outter {border:2px solid red;}*/
        .search-table{table-layout: fixed; margin:40px auto 0px auto; }
        .search-table, td, th{border-collapse:collapse; border:1px solid #777;}
        th{padding:10px 7px; font-size:12px; color:#444;color: whitesmoke}
        td{padding:5px 10px; height:20px;}

        .search-table-outter { overflow-x: scroll; }
        th, td { min-width: 120px; }
    </style>
@endsection

@section('body')
    <div class="overflow-x-auto bg-white rounded-lg shadow relative h-full m-5">
        <table class="border-collapse table-auto w-full whitespace-no-wrap  relative">
            <thead>
                <tr>
                    <th colspan="11" class="text-center py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">Ticket Details</th>
                    <th colspan="{{($fields_count*2)-2}}" class="text-center py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">Ticket Additional Fields</th>
                    <th colspan="{{($replies_count*2)-2}}" class="text-center py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">Ticket Replies</th>
                    <th colspan="{{($approvals_count*3)-3}}" class="text-center py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">Ticket Approvals</th>
                </tr>
                <tr>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Request ID') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Subject') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Requester') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Technician') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Category') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Subcategory') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Status') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Created Date') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Due Date') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Resolved Date') }}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Business Unit') }}</th>


                    @for ($i = 1; $i < $fields_count; $i++)
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Field Name')}}</th>
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Field Value')}}</th>
                    @endfor

                    @for ($i = 1; $i < $replies_count; $i++)
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Reply Content')}}</th>
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Reply Status')}}</th>
                    @endfor

                    @for ($i = 1; $i < $approvals_count; $i++)
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Sent at')}}</th>
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Action date')}}</th>
                        <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Status')}}</th>
                    @endfor


                </tr>
                </thead>
                <tbody>
                @foreach ($data as $ticket)
                    <tr class="hover:bg-yellow-200">
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->id }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->subject }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->requester ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->technician ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->category ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->subcategory ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->status ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->created_at ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->due_date ?? 'Not Assigned' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->resolve_date ? $ticket->resolve_date : ($ticket->close_date ? $ticket->close_date : 'Not Assigned')  }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->business_unit ?? 'Not Assigned' }}</td>


                        @for ($i = 1; $i < $fields_count; $i++)
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->fields[$i]) ? $ticket->fields[$i]->name : '' }}</td>
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->fields[$i]) ? $ticket->fields[$i]->value : '' }}</td>
                        @endfor

                        @for ($i = 1; $i < $replies_count; $i++)
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->replies[$i]) ? strip_tags($ticket->replies[$i]->content) : '' }}</td>
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->replies[$i]) ? $ticket->replies[$i]->status->name : '' }}</td>
                        @endfor

                        @for ($i = 1; $i < $approvals_count; $i++)
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) ? $ticket->approvals[$i]->created_at->format('Y/m/d h:i') : '' }}</td>
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) && $ticket->approvals[$i]->approval_date ? $ticket->approvals[$i]->approval_date->format('Y/m/d h:i') : '' }}</td>
                            <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) ? App\TicketApproval::$statuses[$ticket->approvals[$i]->status] : '' }}</td>
                        @endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
    <div class="text-center">
        {{ $data->links() }}
    </div>
@endsection