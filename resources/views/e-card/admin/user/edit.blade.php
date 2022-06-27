@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit User</h4>

    <form action="{{ route('e-card.admin.user.destroy', $user)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('e-card.admin.user.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-remove"></i></button>
    </form>
@stop


@section('body')
    <form action="{{route('e-card.admin.user.update', $user)}}" method="POST" enctype="multipart/form-data" class="p-5">
        @csrf
{{--    {{ Form::model($user, ['route' => ['admin.user.update', $user],'encrypt'=>'multipart/form-data', 'class' => 'col-sm-9']) }}--}}
    {{ method_field('patch') }}

    @include('e-card.admin.user._form')
    </form>
{{--    {{ Form::close() }}]--}}
@stop
