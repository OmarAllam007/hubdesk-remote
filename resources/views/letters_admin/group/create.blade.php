@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Group</h4>
    <a href="{{ route('letters.letter-group.index') }}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    {{ Form::open(['route' => 'letters.letter-group.store', 'class' => 'col-sm-9']) }}
    @include('letters_admin.group._form')
    {{ Form::close() }}
@stop
