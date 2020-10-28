@extends('layouts.app')

@section('stylesheets')
    <style>
        main {
            background: rgb(240, 242, 245);
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

@section('header')
    {{--    <div style="display: flex; justify-content: space-around;width: 100%">--}}
    {{--        <h4 style="flex-grow: 1">{{t('Tickets')}}</h4>--}}
    {{--        <div style="flex-grow: 1 ;display: flex; justify-content: space-between">--}}
    {{--            {{ Form::open(['route' => 'ticket.scope', 'class' => 'form-inline ticket-scope heading-actions flex']) }}--}}
    {{--            <div class="btn-group" style="">--}}
    {{--                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">--}}
    {{--                    {{t($scopes[$scope])}} &nbsp; <span--}}
    {{--                            class="count">{{\App\Ticket::scopedView($scope)->count()}}</span>--}}
    {{--                    &nbsp; <span class="caret"></span>--}}
    {{--                </button>--}}

    {{--                <ul class="dropdown-menu">--}}
    {{--                    @foreach ($scopes as $key => $value)--}}
    {{--                        <li>--}}
    {{--                            <button class="btn btn-link btn-sm" type="submit" name="scope"--}}
    {{--                                    value="{{$key}}">{{t($value)}}</button>--}}
    {{--                        </li>--}}
    {{--                    @endforeach--}}
    {{--                </ul>--}}
    {{--            </div>--}}

    {{--            {{ Form::close() }}--}}
    {{--            <form action="{{route('ticket.index')}}" class="form-inline heading-actions">--}}
    {{--                <div class="input-group input-group-sm">--}}
    {{--                    <input class="form-control" type="text" name="search" id="search"--}}
    {{--                           placeholder="{{t('Ticket ID / Employee ID')}}"/>--}}
    {{--                    <span class="input-group-btn">--}}
    {{--            <button class="btn btn-default"><i class="fa--}}
    {{--                         @if(\Session::get('personlized-language-ar' . \Auth::user()->id, \Config::get('app.locale'))=="ar")--}}
    {{--                        fa-chevron-left--}}
    {{--                        @else--}}
    {{--                        fa-chevron-right--}}
    {{--                        @endif--}}
    {{--                        "></i></button>--}}
    {{--        </span>--}}
    {{--                </div>--}}
    {{--                --}}{{--<a href="{{ route('ticket.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>--}}
    {{--                <a href="#SearchForm" data-toggle="collapse" class="btn btn-info btn-sm searchbtn"><i--}}
    {{--                            class="fa fa-search"></i></a>--}}
    {{--            </form>--}}
    {{--        </div>--}}
    {{--    </div>--}}

@endsection

@section('body')
    <section  id="TicketList">
        <ticket-index></ticket-index>

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
    <script src="{{asset('/js/ticket-index.js')}}"></script>
@endsection
