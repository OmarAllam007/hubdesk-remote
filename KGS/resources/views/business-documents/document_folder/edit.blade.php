@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Edit Folder')}}</h4>

    <form action="{{ route('kgs.business_documents_folder.destroy', compact('business_unit','folder'))}}" class="pull-right" method="post">
        {{csrf_field()}} {{method_field('delete')}}
        <a href="{{ route('kgs.document.index',compact('business_unit','folder'))}}" class="btn btn-sm btn-default"><i class="fa fa-chevron-left"></i></a>
        <button class="btn btn-sm btn-warning" type="submit"><i class="fa fa-trash-o"></i></button>
    </form>
@stop

@section('body')
    {{ Form::model($folder, ['route' => ['kgs.business_documents_folder.update', 'folder'=>$folder], 'files' => true, 'class' => 'col-sm-6']) }}

        {{ method_field('post') }}

    @include('kgs::business-documents.document_folder._form')

    {{ Form::close() }}
@stop
