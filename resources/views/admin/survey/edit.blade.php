@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Survey</h4>

    <form action="{{route('admin.survey.destroy', $survey)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{route('admin.survey.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    {{Form::model($survey, ['route' => ['admin.survey.update', $survey], 'class' => 'col-sm-9'])}}

    {{method_field('patch')}}

    @include('admin.survey._form')

    {{Form::close()}}
@stop