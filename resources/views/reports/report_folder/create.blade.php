@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create New Folder')}}</h4>
{{--    <a href="{{route('folder.index',compact('folder'))}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>--}}
@stop

{{--@section('sidebar')--}}
{{--	@include('admin.partials._sidebar')--}}
{{--@endsection--}}

@section('body')
    {{Form::open(['route' => ['folder.store'], 'class' => 'col-sm-9'])}}

    @include('reports.report_folder._form')

    {{Form::close()}}
@stop
