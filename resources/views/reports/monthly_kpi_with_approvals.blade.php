@extends('layouts.app')

@section('header')
    <h2>{{ $report->title }}</h2>
    <a href=""></a>
    <div class="btn-toolbar">
        <a href="?excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> {{ t('Excel') }}</a>
        <a href="?pdf" class="btn btn-success btn-sm"><i class="fa fa-file"></i> {{ t('PDF') }}</a>
        <a href="{{route('reports.index')}}" class="btn btn-default btn-sm"><i
                    class="fa fa-chevron-left"></i> {{ t('Back') }}
        </a>
    </div>
@endsection

@section('body')
    <div class="overflow-x-auto bg-white rounded-lg shadow relative h-full m-5">
        <table class="border-collapse table-auto w-full whitespace-no-wrap table-striped relative">
            <thead class="bg-black">
            <tr>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Request ID') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Main Ticket ID') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Subject') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Employee Id') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Requester Name') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Employee/ Requester') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Technician') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Category') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Subcategory') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Status') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Created Date') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Date of Dept.') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Difference') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Due Date') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Resolved Date') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Last working day') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('First Approval Sent Date') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Last Approval Action Date') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Business Unit') }}</th>
                <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{ t('Performance') }}</th>
                @for ($i = 1; $i < $approvals_count; $i++)
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Description')}}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Sent at')}}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Action date')}}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Total Time (In Days)')}}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Status')}}</th>
                    <th class="py-3 px-6 sticky top-0 border-b border border-gray-600 bg-blue-600 text-white">{{t('Approval '.$i.' Comment')}}</th>
                @endfor
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $ticket)

                <tr class="hover:bg-yellow-200">
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->id }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->request_id ?? '' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->subject }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->employee_id ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->requester ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->employee_name ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->technician ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->category ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->subcategory ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->status ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->created_at->format('Y/m/d h:m') ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->date_of_dept ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->difference ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->due_date->format('Y/m/d h:m') ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->resolve_date ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->last_working_day ?  $ticket->last_working_day : 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->approvals->first() ? $ticket->approvals->last()->created_at->format('Y/m/d H:m') : 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ ($ticket->approvals->last() && $ticket->approvals->last()->approval_date) ? $ticket->approvals->last()->approval_date->format('Y/m/d H:m') : 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->business_unit ?? 'Not Assigned' }}</td>
                    <td class="py-4 px-6  border border-gray-600 ">{{ $ticket->performance ?? 'Not Assigned' }}</td>

                    @for ($i = 1; $i < $approvals_count; $i++)
                        <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) ? str_replace('&nbsp;',' ',strip_tags($ticket->approvals[$i]->content)) : '' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) ? $ticket->approvals[$i]->created_at->format('Y/m/d H:m') : '' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) && $ticket->approvals[$i]->approval_date ? $ticket->approvals[$i]->approval_date->format('Y/m/d H:m') : '' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) && $ticket->approvals[$i]->approval_date ? $ticket->approvals[$i]->approval_date->diffInDays($ticket->approvals[$i]->created_at) : '' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) ? App\TicketApproval::$statuses[$ticket->approvals[$i]->status] : '' }}</td>
                        <td class="py-4 px-6  border border-gray-600 ">{{ isset($ticket->approvals[$i]) ? str_replace('&nbsp;',' ',strip_tags($ticket->approvals[$i]->comment)) : '' }}</td>
                    @endfor
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="w-full text-center">
        {{ $data->links() }}
    </div>
@endsection