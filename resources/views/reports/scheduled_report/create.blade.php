@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Scheduled Report')}}</h4>
    <a href="{{route('reports.index')}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop


@section('body')
    {{Form::open(['route' => ['reports.scheduled_report.store'], 'class' => 'col-sm-9'])}}

    @include('reports.scheduled_report._form')


    {{Form::close()}}
@endsection

@section('javascript')
    <script src="{{asset('js/reports.js')}}"></script>
@endsection
