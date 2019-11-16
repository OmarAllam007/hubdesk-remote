@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Edit Document')}}</h4>

    <form action="{{ route('kgs.document.destroy', compact('folder','document'))}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('kgs.document.index',compact('folder','document'))}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('body')
    {{ Form::model($document, ['route' => ['kgs.document.update', 'folder'=>$folder,'document'=>$document], 'files' => true, 'class' => 'col-sm-12']) }}

        {{ method_field('patch') }}

    @include('kgs::business-documents.documents._form')

    {{ Form::close() }}
@stop
