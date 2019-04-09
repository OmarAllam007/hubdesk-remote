@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Create Document')}}</h4>

    <a href="{{ route('kgs.document.index',compact('business_unit'))}}" class="btn btn-sm btn-default pull-right"><i
                class="fa fa-chevron-left"></i></a>
@stop

@section('body')

    {{ Form::open(['route' => ['kgs.document.store','business_unit'=>$business_unit], 'files' => true, 'class' => 'col-sm-6']) }}

    @include('kgs::business-documents.documents._form')

    {{ Form::close() }}
@stop
