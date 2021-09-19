@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Header</h4>
    <a href="{{ route('letters.header.index') }}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    {{ Form::open(['route' => 'letters.header.store', 'class' => 'col-sm-9','enctype'=>"multipart/form-data"]) }}
    @include('letters_admin.header._form')
    {{ Form::close() }}
@stop
