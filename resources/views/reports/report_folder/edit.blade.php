@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Edit Folder')}}</h4>

    <form action="{{route('folder.destroy', compact('folder'))}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{route('folder.index',compact('folder'))}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('sidebar')
    @include('admin.partials._sidebar')
@stop

@section('body')
    {{Form::model($folder, ['route' => ['folder.update',$folder], 'class' => 'col-sm-9'])}}

    {{method_field('patch')}}

    @include('reports.report_folder._form')

    {{Form::close()}}
@stop