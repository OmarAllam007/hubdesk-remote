@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Question</h4>
    <a href="{{route('admin.question.index',compact('survey'))}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
	@include('admin.partials._sidebar')
@endsection

@section('body')
    {{Form::open(['route' => ['admin.question.store',$survey], 'class' => 'col-sm-9'])}}

    @include('admin.question._form')

    {{Form::close()}}
@stop
