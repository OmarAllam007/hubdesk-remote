@extends('layouts.app')

@section('header')
    <h4>Add Reply Template</h4>
    <a href="{{route('reply_template.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('user.configurations._sidebar')
@stop

@section('body')
    {{Form::open(['route' => 'reply_template.store', 'class' => 'col-sm-9'])}}

    @include('user.configurations.reply_template._form')

    {{Form::close()}}
@stop