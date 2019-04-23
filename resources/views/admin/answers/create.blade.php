@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add Answer</h4>
    <a href="{{route('admin.answer.index',compact('question'))}}" class="btn btn-sm btn-default pull-right"><i class="fa fa-chevron-left"></i></a>
@stop

@section('sidebar')
	@include('admin.partials._sidebar')
@endsection

@section('body')
    {{Form::open(['route' => ['admin.answer.store',$question], 'class' => 'col-sm-9'])}}

    @include('admin.answers._form')

    {{Form::close()}}
@stop
