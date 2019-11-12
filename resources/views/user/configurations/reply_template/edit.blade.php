@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Reply Template</h4>

    <form action="{{route('reply_template.edit', $template)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{route('reply_template.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('user.configurations._sidebar')
@stop

@section('body')
    {{Form::model($reply_template, ['route' => ['reply_template.update', $reply_template], 'class' => 'col-sm-8'])}}

    {{method_field('patch')}}

    @include('user.user.configurations.reply_template._form')

    {{Form::close()}}
@stop