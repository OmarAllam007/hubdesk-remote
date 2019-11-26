@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Subcategory</h4>

    <a href="{{route('kgs.admin.subcategory.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    {{Form::open(['route' => 'kgs.admin.subcategory.store', 'class' => 'col-sm-9'])}}

    @include('kgs::admin.subcategory._form')

    {{Form::close()}}
@stop
