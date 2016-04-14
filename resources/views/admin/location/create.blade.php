@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Location</h4>

    <a href="{{route('admin.location.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('body')
    {{Form::open(['route' => 'admin.location.store'])}}

    @include('admin.location._form')

    {{Form::close()}}
@stop