@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{isset($item) ? $item->canonicalName() . '>' : ''}} Add SubItem</h4>

    <a href="{{route('admin.subItem.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    {{Form::open(['route' => 'admin.subItem.store', 'class' => 'col-sm-9'])}}

    @include('admin.subitem._form')

    {{Form::close()}}
@stop
