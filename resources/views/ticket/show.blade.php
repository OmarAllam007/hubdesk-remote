@extends('layouts.app')

@section('body')
    <style>
        #wrapper {
            background: #eaeaea;
        }
    </style>
    <div id="ticketShow">
        <ticket-show :data="{{json_encode(\App\Http\Resources\TicketResource::make($ticket))}}"></ticket-show>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('/js/ticket/ticket-show.js')}}?version={{time()}}"></script>
@endsection