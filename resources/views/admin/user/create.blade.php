@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add User</h4>

    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    <form action="{{route('admin.user.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.user._form')

    {{ Form::close() }}
@stop
