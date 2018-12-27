@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Role</h4>

    <form action="{{ route('admin.role.destroy', $role)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('admin.role.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    {{ Form::model($role, ['route' => ['admin.role.update', $role], 'class' => 'col-sm-9']) }}

        {{ method_field('patch') }}

        @include('admin.role._form')

    {{ Form::close() }}
@stop
