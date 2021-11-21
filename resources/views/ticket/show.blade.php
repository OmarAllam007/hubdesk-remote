@extends('layouts.app')

@section('body')
    <style>
        #wrapper {
            background: #eaeaea;
        }
    </style>
    <div id="ticketShow">
        @php
            $language =\Session::get('personalized-language' . \Auth::user()->id, \Config::get('app.locale'));
        @endphp
        <ticket-show :data="{{json_encode(\App\Http\Resources\TicketResource::make($ticket))}}"
                     :translations="{{json_encode(\App\Translation::where('language',$language)->get(['word','translation']))}}"></ticket-show>
    </div>
@endsection

@section('javascript')
{{--    ?version={{time()}}--}}
    <script src="{{asset('/js/ticket/ticket-show.js')}}"></script>
@endsection