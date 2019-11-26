@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Admin Panel</h4>
{{--    <a href="{{route('admin.item.create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>--}}
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')

@stop
