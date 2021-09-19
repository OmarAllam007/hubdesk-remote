@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Field</h4>

    <form action="{{ route('letters.letter-field.destroy', $letter_field)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('letters.letter-field.index')}}" class="btn btn-sm btn-default"><i
                    class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    {{ Form::model($letter_field, ['route' => ['letters.letter-field.update', $letter_field], 'class' => 'col-sm-9']) }}

    {{ method_field('patch') }}

    @include('letters_admin.letter_field._form')

    {{ Form::close() }}
@stop
