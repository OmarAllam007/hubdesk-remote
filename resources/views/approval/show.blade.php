@extends('layouts.app')

{{--@section('header')--}}
{{--    --}}
{{--@stop--}}

@section('body')
    <style>
        #wrapper {
            background: #eaeaea;
        }
    </style>
    <div id="ticketShow">
        <div id="TicketApprovalShow">
            <approval-show :approval="{{json_encode($ticketApproval)}}"
                           :questions="{{json_encode($ticketApproval->questions ?? '{}')}}"
                           :requester="{{json_encode($ticketApproval->ticket->requester->toRequesterJson(false))}}"
                           :fields="{{json_encode($ticketApproval->ticket->custom_fields)}}"
                           :replies="{{json_encode(\App\Http\Resources\TicketReplyResource::collection($ticketApproval->ticket->replies()->latest()->get()))}}"
                           :approvals="{{json_encode(\App\Http\Resources\TicketApprovalResource::collection($ticketApproval->ticket->approvals()->latest()->get()))}}"
                           :load_from_sap="{{json_encode(in_array($ticketApproval->ticket->category_id,[104]))}}"
            >
            </approval-show>
        </div>
    </div>

    <section class="ticket">
        <div class="col-md-6">
            <section id="action">

            </section>
        </div>
        <div class="col-md-6">

            {{--            @include('ticket.partials._requester_details',['ticket'=>$ticketApproval->ticket])--}}
            {{--                        @include('ticket.partials._ticket_additional_fields',['ticket'=>$ticketApproval->ticket])--}}
            {{--                        @include('ticket.partials._ticket_replies',['ticket'=>$ticketApproval->ticket])--}}
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket/ticket-approval-show.js')}}?version={{time()}}"></script>
@endsection