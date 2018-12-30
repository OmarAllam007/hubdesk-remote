@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Group</h4>

    <a href="{{ route('admin.role.index') }}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    {{ Form::open(['route' => 'admin.role.store', 'class' => 'col-sm-9']) }}

        @include('admin.role._form')

    {{ Form::close() }}
@stop 
