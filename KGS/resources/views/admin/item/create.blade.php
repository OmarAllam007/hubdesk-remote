@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Item</h4>

    <a href="{{route('kgs.admin.item.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    {{Form::open(['route' => 'kgs.admin.item.store', 'class' => 'col-sm-9'])}}

    @include('kgs::admin.item._form')

    {{Form::close()}}
@stop
