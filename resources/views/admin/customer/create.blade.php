@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Add Customer')}}</h4>

    <a href="{{route('admin.customer.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    {{Form::open(['route' => 'admin.customer.store', 'class' => 'col-sm-9'])}}

    @include('admin.customer._form')

    {{Form::close()}}
@stop