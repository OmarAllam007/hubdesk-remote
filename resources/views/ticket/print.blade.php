@extends('layouts.print')


@section('stylesheets')
    <style>
        @media print {
            .header{
                display: none;
            }

        }
    </style>
@endsection

@section('body')

    <div class="flex justify-end header">
        <a href="{{route('ticket.show',$ticket)}}" class="py-2 px-4 border border-transparent shadow-sm
                text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <i class="fa fa-chevron-left" aria-hidden="true"></i> {{t('Back to Ticket')}}
        </a>
    </div>

    <div class="header">
        @include('ticket.print._header')
    </div>


    <div class="flex flex-col m-5 print-ticket-basic">
        <div>
            <img src="{{asset('/images/logo.png')}}" class="object-cover">
        </div>

        <div class="flex justify-between mt-4">
            <h4><strong>{{t('Request ID')}}</strong> : {{$ticket->id}}</h4>
            <h4><strong>{{t('Subject')}}:</strong> : {{$ticket->subject}}</h4>

            <h4>
                @if ($ticket->sdp_id) <strong>Helpdesk</strong> : {{$ticket->sdp_id ?? ''}} &mdash; @endif
                {{t('By')}} :{{$ticket->requester->name}}
            </h4>
        </div>
        <div class="flex justify-between">

            <small><strong>{{t('Created at')}}
                    :</strong> {{$ticket->created_at->format('d/m/Y H:i')}}
            </small>
            <div>
                @if($ticket->overdue)
                    <i class="fa fa-flag text-red-700" aria-hidden="true"
                       title="{{t('SLA violated')}}"></i>
                @endif
                <small><strong>{{t('Status')}}:</strong> {{$ticket->status->name}}</small>
            </div>


        </div>
    </div>

    @if($ticket->description)
        <div class="flex flex-col m-5 print-ticket-basic">
            <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
                <div class="p-3"> {!! tidy_repair_string($ticket->description, [], 'utf8') !!}</div>
            </div>
        </div>
    @endif


    <div class="print-ticket-details">
        @include('ticket.print._request_details')
    </div>

    <div class="print-ticket-requester-details page-break">
        @include('ticket.print._requester_details')
    </div>

    @if ($ticket->replies->count())
        <div class="print-ticket-conversation page-break">
            @include('ticket.print._replies')
        </div>
    @endif

    @if ($ticket->approvals->count())
        <div class="print-ticket-approvals page-break">
            @include('ticket.print._approvals')
        </div>
    @endif

    @if ($ticket->resolution)
        <div class="print-ticket-resolution">
            @include('ticket.print._resolution')
        </div>
    @endif


    @if ($ticket->notes->count())
        <div class="print-ticket-notes">
            @include('ticket.print._notes')
        </div>
    @endif





@endsection
@section('javascript')

    <script src="{{asset('/js/print.js')}}"></script>
@endsection