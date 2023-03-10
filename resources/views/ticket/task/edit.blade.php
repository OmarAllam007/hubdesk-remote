@extends('layouts.app')

@section('body')
    <div id="ticketArea">
        {{ Form::model($task, ['route' => ['tasks.update', $task], 'class' => 'col-sm-12','enctype'=>'multipart/form-data']) }}
        {{ method_field('patch') }}
        @include('ticket.task._form')
        {{ Form::close() }}
    </div>

@endsection

@section('javascript')
    <script>
        var category = '{{Form::getValueAttribute('category_id') ?? $task->category_id}}';
        var subcategory = '{{Form::getValueAttribute('subcategory_id') ?? $task->subcategory_id}}';
        var item = '{{Form::getValueAttribute('item_id') ?? $task->item_id}}';
        var group = '{{Form::getValueAttribute('group_id') ?? $task->group_id}}'
        var technician_id = '{{Form::getValueAttribute('technician_id') ?? $task->technician_id}}'
    </script>
    <script src="{{asset('/js/ticket-form.js')}}"></script>
    <script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>
@append