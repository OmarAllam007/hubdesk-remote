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
    <div class="container  col-md-12">
        <section class="report-horizontal-scroll">
            <table class="table ">
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
                    <th>{{ t('Date of Dept.') }}</th>
                    <th>{{ t('Difference') }}</th>
                    <th>{{ t('Due Date') }}</th>
                    <th>{{ t('Resolved Date') }}</th>
                    <th>{{ t('Business Unit') }}</th>
                    @for ($i = 1; $i <= $approvals_count; $i++)
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
                        <td>{{ $ticket->date_of_dept ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->difference ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->due_date ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->resolve_date ?? 'Not Assigned' }}</td>
                        <td>{{ $ticket->business_unit ?? 'Not Assigned' }}</td>

                        @foreach($ticket->approvals as $key=>$approval)
                            <td>{{ $approval->created_at->format('Y/m/d h:i') }}</td>
                            <td>{{ $approval->approval_date ? $approval->approval_date->format('Y/m/d h:i') : ''}}</td>
                            <td>{{ $approval->status ? App\TicketApproval::$statuses[$approval->status] : ''}}</td>
                        @endforeach
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