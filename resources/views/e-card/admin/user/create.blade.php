@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Add User</h4>

    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop


@section('body')
    <form action="{{route('e-card.admin.store')}}" method="POST" enctype="multipart/form-data"
    class="p-5 ">
    @csrf
    @include('e-card.admin.user._form')

    {{ Form::close() }}
@stop
