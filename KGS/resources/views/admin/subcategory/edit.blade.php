@extends('layouts.app')

@section('header')
    <h4 class="pull-left">Edit Subcategory</h4>

    <form action="{{route('kgs.admin.subcategory.destroy', $subcategory)}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{route('kgs.admin.subcategory.index')}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    {{Form::model($subcategory, ['route' => ['kgs.admin.subcategory.update', $subcategory], 'class' => 'col-sm-9'])}}

    {{method_field('post')}}

    @include('kgs::admin.subcategory._form')

    {{Form::close()}}
@stop
