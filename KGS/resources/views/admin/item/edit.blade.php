@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Item</h4>

    <form action="{{route('kgs.admin.item.destroy', $item)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{route('kgs.admin.item.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    {{Form::model($item, ['route' => ['kgs.admin.item.update', $item], 'class' => 'col-sm-9'])}}

    {{method_field('post')}}

    @include('kgs::admin.item._form')

    {{Form::close()}}
@stop
