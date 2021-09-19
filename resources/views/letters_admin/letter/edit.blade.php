@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Letter</h4>

    <form action="{{ route('letters.letter.destroy', $letter)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('letters.letter.index')}}" class="btn btn-sm btn-default"><i
                    class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('letters_admin._partial._sidebar')
@stop

@section('body')
    {{ Form::model($letter, ['route' => ['letters.letter.update', $letter], 'class' => 'col-sm-9']) }}

    {{ method_field('patch') }}

    @include('letters_admin.letter._form')

    {{ Form::close() }}
@stop
