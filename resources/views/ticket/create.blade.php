@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Ticket')}}</h4>

    <a href="{{ route('ticket.index')}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('body')

    {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12']) }}

    @include('ticket._form')

    {{ Form::close() }}
@endsection
@section('javascript')
    <script>
        var category = '{{Form::getValueAttribute('category_id')}}';
        var subcategory = '{{Form::getValueAttribute('subcategory_id')}}';
        var item = '{{Form::getValueAttribute('item_id')}}';
        var group = '{{Form::getValueAttribute('group_id')}}';
    </script>
    <script src="{{asset('/js/ticket-form.js')}}"></script>
    <script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>
@append