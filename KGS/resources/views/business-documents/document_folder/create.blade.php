@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Document')}}</h4>

    <a href="{{URL::previous()}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('body')

    {{ Form::open(['route' => ['kgs.business_documents_folder.store','business_unit'=>$business_unit], 'files' => true, 'class' => 'col-sm-6']) }}

    @include('kgs::business-documents.document_folder._form')

    {{ Form::close() }}
@stop
