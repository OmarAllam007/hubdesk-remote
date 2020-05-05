@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Ticket')}}</h4>

    <a href="{{ route('ticket.index')}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('body')

    {{ Form::open(['route' => 'ticket.store', 'files' => true, 'class' => 'col-sm-12','id'=>'ticketArea']) }}

    @include('ticket._form')

    {{ Form::close() }}
@endsection
@section('javascript')
    <script>
        var category = '{{Form::getValueAttribute('category_id') ? Form::getValueAttribute('category_id') : isset($category) ? $category->id : null}}';
        var subcategory = '{{request('subcategory_id') ? Form::getValueAttribute('subcategory_id', request('subcategory_id')) :  isset($subcategory) ? $subcategory->id : null}}';
        var item = '{{request('item_id') ? Form::getValueAttribute('item_id',request('item_id')) :  isset($item) ? $item->id : null}}';
    </script>
    <script src="{{asset('/js/ticket-form.js')}}"></script>
    <script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>
@append