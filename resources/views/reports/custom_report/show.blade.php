@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{$report->title}}</h4>
    <a href="{{route('reports.index')}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop


@section('body')
    <div class="col-md-12">
        @if($report->parameters['group_by'] != null)
           @include('reports.custom_report._grouped')
        @else
           @include('reports.custom_report._not_grouped')
        @endif

    </div>

@stop
