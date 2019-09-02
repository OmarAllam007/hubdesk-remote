@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Edit Query Report')}}</h4>
    <a href="{{route('reports.index')}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop


@section('body')
    {{Form::open(['route' => ['reports.custom_report.update',$report], 'class' => 'col-sm-9'])}}

    @include('reports.custom_report._form')

    {{Form::close()}}
@stop
