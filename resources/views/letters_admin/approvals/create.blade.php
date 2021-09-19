@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Approval</h4>
    <a href="{{ route('letters.approval.index') }}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    {{ Form::open(['route' => 'letters.approval.store', 'class' => 'col-sm-9']) }}
    @include('letters_admin.approvals._form')
    {{ Form::close() }}
@stop
