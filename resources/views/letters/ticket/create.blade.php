@extends('layouts.app')

{{--@section('header')--}}
{{--    <h4 class="pull-left">{{t('Create Ticket')}}</h4>--}}

{{--    <a href="{{ route('ticket.index')}}" class="btn btn-sm btn-default pull-right"><i--}}
{{--                class="fa fa-chevron-left"></i></a>--}}
{{--@stop--}}

@section('body')

    {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12','id'=>'ticketArea']) }}

    @include('letters.ticket._form')

    {{ Form::close() }}
@endsection

@section('javascript')
    <script src="{{asset('/js/letters/letters.js')}}"></script>
@endsection