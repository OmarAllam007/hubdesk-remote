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
    <div class="container  col-md-12">
        <section class="report-horizontal-scroll search-table-outter">
            <table class=" search-table inner">
                <thead style="background-color: #194F7E;color: white">
                <tr>
                    <th>{{ t('Request ID') }}</th>
                    <th>{{ t('Subject') }}</th>
                    <th>{{ t('Requester') }}</th>
                    <th>{{ t('Technician') }}</th>
                    <th>{{ t('Category') }}</th>
                    <th>{{ t('Subcategory') }}</th>
                    <th>{{ t('Status') }}</th>
                    <th>{{ t('Created Date') }}</th>
                    <th>{{ t('Difference') }}</th>
                    <th>{{ t('Due Date') }}</th>
                    <th>{{ t('Resolved Date') }}</th>
                    <th>{{ t('Business Unit') }}</th>
                    @for ($i = 1; $i < $approvals_count; $i++)
                        <th>{{t('Approval '.$i.' Sent at')}}</th>
                        <th>{{t('Approval '.$i.' Action date')}}</th>
                        <th>{{t('Approval '.$i.' Status')}}</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>{{ $ticket->requester ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->technician ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->category ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->subcategory ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->status ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->created_at ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->difference ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->due_date ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->resolve_date ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->business_unit ?? 'Not Assigned' }}</td>

                        @for ($i = 1; $i < $approvals_count; $i++)
                            <td>{{ isset($ticket->approvals[$i]) ? $ticket->approvals[$i]->created_at->format('Y/m/d h:i') : '' }}</td>
                            <td>{{ isset($ticket->approvals[$i]) && $ticket->approvals[$i]->approval_date ? $ticket->approvals[$i]->approval_date->format('Y/m/d h:i') : '' }}</td>
                            <td>{{ isset($ticket->approvals[$i]) ? App\TicketApproval::$statuses[$ticket->approvals[$i]->status] : '' }}</td>
                        @endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    </div>
    <div class="text-center">
        {{ $data->links() }}
    </div>
@endsection