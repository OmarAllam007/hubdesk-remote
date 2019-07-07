@extends('layouts.app')

@section('header')
    <h4 class="flex">{{t('Tickets')}}</h4>

    {{ Form::open(['route' => 'ticket.scope', 'class' => 'form-inline ticket-scope heading-actions flex']) }}
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            {{t($scopes[$scope])}} &nbsp; <span class="count">{{\App\Ticket::scopedView($scope)->count()}}</span>
            &nbsp; <span class="caret"></span>
        </button>

        <ul class="dropdown-menu">
            @foreach ($scopes as $key => $value)
                <li>
                    <button class="btn btn-link btn-sm" type="submit" name="scope"
                            value="{{$key}}">{{t($value)}}</button>
                </li>
            @endforeach
        </ul>
    </div>
    {{ Form::close() }}

    {{Form::open(['route' => 'ticket.jump', 'class' => 'form-inline heading-actions'])}}
    <div class="input-group input-group-sm">
        <input class="form-control" type="text" name="id" id="ticketID" placeholder="{{t('Ticket ID')}}"/>
        <span class="input-group-btn">
            <button class="btn btn-default"><i class="fa
                         @if(\Session::get('personlized-language-ar' . \Auth::user()->id, \Config::get('app.locale'))=="ar")
                        fa-chevron-left
                        @else
                        fa-chevron-right
                        @endif
                        "></i></button>
        </span>
    </div>
    {{--<a href="{{ route('ticket.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>--}}
    <a href="#SearchForm" data-toggle="collapse" class="btn btn-info btn-sm searchbtn"><i class="fa fa-search"></i></a>
    {{Form::close()}}
    <style>
        .ticket-card {
            display: flex;
            margin: 5px;
            background-color: white;
            padding: 20px;
            padding-right: 100px;
            width: 80%;
            justify-content: space-between;
            box-shadow: 2px 2px 2px darkgrey;
            border-left: 2px solid #636b6f;
            /*border-radius: 5px;*/
        }

        .ticket-container {
            display: flex;
            flex-direction: column;
            /*justify-content: center;*/
            /*align-content: center;*/
            align-items: center;
        }

        .subject {
            font-size: 12pt;
            font-weight: bold;
        }
    </style>
@stop

@section('body')
    {{--<div class="ticket-container" style="background-color: #ecf1f4">--}}
    {{--@foreach($tickets as $ticket)--}}

    {{--<div class="ticket-card">--}}
    {{--<div class="ticket-details">--}}
    {{--<p style="border: 1px solid dodgerblue;color:dodgerblue;text-align: center;height: 20px;padding: 0 5px 0 5px">Waiting Customer Response</p>--}}
    {{--<p class="subject">#1234 </p>--}}
    {{--<p class="subject">Access to System </p>--}}
    {{--<p>Requester: Omar</p>--}}
    {{--<p>Technician: Ahmed</p>--}}
    {{--</div>--}}
    {{--<div class="ticket-details2">--}}
    {{--<p style="height: 20px"></p>--}}
    {{--<p class="subject">Helpdesk Issues</p>--}}
    {{--<p>Created At: 25-06-2019</p>--}}
    {{--<p>Due At: 25-08-2019</p>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="ticket-card">--}}
    {{--<div class="ticket-details">--}}
    {{--<p style="border: 1px solid green;color:green;text-align: center;height: 20px;padding: 0 5px 0 5px">New</p>--}}
    {{--<p class="subject">#1234 </p>--}}
    {{--<p class="subject">Access to System </p>--}}
    {{--<p>Requester: Omar</p>--}}
    {{--<p>Technician: Ahmed</p>--}}
    {{--</div>--}}
    {{--<div class="ticket-details2">--}}
    {{--<p style="height: 20px"><i class="fa fa-warning" style="color: darkred;font-size: 12pt"></i></p>--}}
    {{--<p class="subject">Helpdesk Issues</p>--}}
    {{--<p>Created At: 25-06-2019</p>--}}
    {{--<p>Due At: 25-08-2019</p>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--</div>--}}


    <section class="col-sm-12" id="TicketList">
        @include('ticket._search_form')
        @if ($tickets->total())
            <table class="table table-striped index-table" id="index-table" style="  border-collapse: collapse;
  border-radius: 1em;
  overflow: hidden;box-shadow: 0 0 3px">
                <thead style="  padding: 1em;">
                <tr>
                    <th>{{t('ID')}}</th>
                    {{--<th>{{t('Helpdesk ID')}}</th>--}}
                    <th>{{t('Subject')}}</th>
                    <th>{{t('Requester')}}</th>
                    <th>{{t('SAP ID')}}</th>
                    <th>{{t('Technician')}}</th>
                    <th>{{t('Created At')}}</th>
                    <th>{{t('Due Date')}}</th>
                    <th>{{t('Status')}}</th>
                    <th>{{t('Category')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td><i class="fa fa-{{t($ticket->type_icon)}}" title="{{t($ticket->type_name)}}"
                               aria-hidden="true"></i> <a
                                    href="{{ route('ticket.show', $ticket) }}">{{ $ticket->id }}</a></td>
                        {{--                        <td><a href="{{ route('ticket.show', $ticket) }}">{{ $ticket->sdp_id ?? ''}}</a></td>--}}
                        <td>
                            @if($ticket->overdue)
                                <i class="fa fa-flag text-danger" aria-hidden="true" title="SLA violated"></i>
                            @endif
                            <a href="{{ route('ticket.show', $ticket) }}">{{ $ticket->subject }}</a>
                        </td>
                        <td>{{ $ticket->requester->name }}</td>
                        <td>{{ $ticket->requester->employee_id ?? t('Not Assigned') }}</td>
                        <td>{{ $ticket->technician? $ticket->technician->name : 'Not Assigned' }}</td>
                        <td>{{ $ticket->created_at->format('d/m/Y h:i a') }}</td>
                        <td>{{ $ticket->due_date? $ticket->due_date->format('d/m/Y h:i a') : t('Not Assigned') }}</td>
                        <td>{{ t($ticket->status->name) }}</td>
                        <td>{{ t($ticket->category->name) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @include('partials._pagination', ['items' => $tickets])
        @else
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <strong>No tickets found</strong>
            </div>
        @endif
    </section>
@stop

@section('javascript')
    <script src="{{asset('/js/ticket-index.js')}}"></script>
@endsection
