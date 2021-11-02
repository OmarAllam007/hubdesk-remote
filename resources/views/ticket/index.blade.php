@extends('layouts.app')

@section('stylesheets')
    <style>
        #wrapper {
            background: #eaeaea;
        }

        .ticket-card {
            /*box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);*/
            /*-webkit-box-shadow: 0 3px 2px 0 rgba(0, 0, 0, 0.05);*/
            box-shadow: -1px 2px 1px 0 rgb(80 80 80 / 15%);
            position: relative;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            /*-webkit-box-orient: vertical;*/
            /*-webkit-box-direction: normal;*/
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            /*background-clip: border-box;*/
            /*border: 1px solid rgba(0, 40, 100, 0.12);*/
            border-radius: 10px;
        }
    </style>
@endsection

@section('body')
    @php
        $lastTickets = \Session::get('recent-tickets-' . auth()->id(), []);

    @endphp

    <section id="TicketList">
        <ticket-index
                :criterions="{{json_encode(session('ticket.filter'))}}"
                :last-tickets="{{json_encode($lastTickets)}}"
        ></ticket-index>
        {{--        @include('ticket._search_form')--}}
        {{--        --}}
        {{--        @include('ticket._search_form')--}}
        {{--        @if ($tickets->total())--}}
        {{--            <table class="table table-striped index-table" id="index-table" style="  border-collapse: collapse;--}}
        {{--  border-radius: 1em;--}}
        {{--  overflow: hidden;box-shadow: 0 0 3px">--}}
        {{--                <thead style="  padding: 1em;">--}}
        {{--                <tr>--}}
        {{--                    <th>{{t('ID')}}</th>--}}
        {{--                    --}}{{--<th>{{t('Helpdesk ID')}}</th>--}}
        {{--                    <th>{{t('Subject')}}</th>--}}
        {{--                    <th>{{t('Requester')}}</th>--}}
        {{--                    <th>{{t('SAP ID')}}</th>--}}
        {{--                    <th>{{t('Coordinator')}}</th>--}}
        {{--                    <th>{{t('Created At')}}</th>--}}
        {{--                    <th>{{t('Due Date')}}</th>--}}
        {{--                    <th>{{t('Status')}}</th>--}}
        {{--                    <th>{{t('Category')}}</th>--}}
        {{--                    <th>{{t('Subcategory')}}</th>--}}
        {{--                </tr>--}}
        {{--                </thead>--}}
        {{--                <tbody>--}}
        {{--                @foreach($tickets as $ticket)--}}
        {{--                    <tr @if($ticket->is_opened_ticket) class="ticket_url" @endif>--}}
        {{--                        <td><i class="fa fa-{{t($ticket->type_icon)}} " title="{{t($ticket->type_name)}}"--}}
        {{--                               aria-hidden="true"></i> <a--}}
        {{--                                    href="{{ route('ticket.show', $ticket) }}">{{ $ticket->id }}</a></td>--}}
        {{--                        --}}{{--                        <td><a href="{{ route('ticket.show', $ticket) }}">{{ $ticket->sdp_id ?? ''}}</a></td>--}}
        {{--                        <td>--}}
        {{--                            @if($ticket->overdue)--}}
        {{--                                <i class="fa fa-flag text-danger" aria-hidden="true" title="SLA violated"></i>--}}
        {{--                            @endif--}}
        {{--                            <a href="{{ route('ticket.show', $ticket) }}">{{ $ticket->subject }}</a>--}}
        {{--                        </td>--}}
        {{--                        <td>{{ $ticket->requester->name ?? t('Not Assigned') }}</td>--}}
        {{--                        <td>{{ $ticket->requester->employee_id ?? t('Not Assigned') }}</td>--}}
        {{--                        <td>{{ $ticket->technician? $ticket->technician->name : 'Not Assigned' }}</td>--}}
        {{--                        <td>{{ $ticket->created_at->format('d/m/Y h:i a') }}</td>--}}
        {{--                        <td>{{ $ticket->due_date? $ticket->due_date->format('d/m/Y h:i a') : t('Not Assigned') }}</td>--}}
        {{--                        <td>{{ t($ticket->status->name) }}</td>--}}
        {{--                        <td>{{ t($ticket->category->name ?? 'Not Assigned') }}</td>--}}
        {{--                        <td>{{ t($ticket->subcategory->name ?? 'Not Assigned') }}</td>--}}
        {{--                    </tr>--}}
        {{--                @endforeach--}}
        {{--                </tbody>--}}
        {{--            </table>--}}

        {{--            @include('partials._pagination', ['items' => $tickets])--}}
        {{--        @else--}}
        {{--            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No tickets found</strong>--}}
        {{--            </div>--}}
        {{--        @endif--}}
    </section>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket-index.js')}}?version={{time()}}"></script>
@endsection