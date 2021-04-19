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
                           :requester="{{json_encode($ticketApproval->ticket->requester->toRequesterJson())}}"
                           :fields="{{json_encode($ticketApproval->ticket->custom_fields)}}"
                           :replies="{{json_encode(\App\Http\Resources\TicketReplyResource::collection($ticketApproval->ticket->replies()->latest()->get()))}}"
                           :approvals="{{json_encode(\App\Http\Resources\TicketApprovalResource::collection($ticketApproval->ticket->approvals()->latest()->get()))}}"

            >
            </approval-show>
        </div>
    </div>


    {{--    <div class="p-5 m-5">--}}
    {{--        {{Form::open(['route' => ['approval.update', $ticketApproval]])}}--}}



    {{--        @if(!$ticketApproval->questions->count())--}}
    {{--            <h4>Action</h4>--}}
    {{--            <div class="row form-group {{$errors->has('status')? 'has-error' : ''}}">--}}
    {{--                <div class="col-md-3">--}}
    {{--                    <label for="approve" class="radio-online">--}}
    {{--                        {{Form::radio('status', \App\TicketApproval::APPROVED, null, ['id' => 'approve'])}}--}}
    {{--                        {{t('Approve')}}--}}
    {{--                        --}}{{--                            <i class="fa fa-thumbs-o-up"></i>--}}
    {{--                    </label>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-3">--}}
    {{--                    <label for="deny" class="radio-online">--}}
    {{--                        {{Form::radio('status', \App\TicketApproval::DENIED, null, ['id' => 'deny'])}}--}}
    {{--                        {{t('Deny')}}--}}
    {{--                        --}}{{--                            <i class="fa fa-thumbs-o-down"></i>--}}
    {{--                    </label>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-12">--}}
    {{--                    @if($errors->has('status'))--}}
    {{--                        <div class="error-message">{{$errors->first('status')}}</div>--}}
    {{--                    @endif--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        @endif--}}

    {{--        <div class="form-group">--}}
    {{--        </div>--}}

    {{--        {{Form::close()}}--}}
    {{--    </div>--}}

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