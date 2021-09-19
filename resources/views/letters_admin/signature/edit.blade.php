@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Signature</h4>

    <form action="{{ route('letters.signature.destroy', $signature)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('letters.letter-group.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    {{ Form::model($signature, ['route' => ['letters.signature.update', $signature], 'class' => 'col-sm-9']) }}

        {{ method_field('patch') }}

        @include('letters_admin.signature._form')

    {{ Form::close() }}
@stop
