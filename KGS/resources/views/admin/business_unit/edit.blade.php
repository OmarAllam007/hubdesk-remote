@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Edit Business Units')}}</h4>
@stop

@section('sidebar')
    @include('kgs::admin.partials._sidebar')
@stop

@section('body')
    {{Form::open(['route' => ['kgs.admin.business_unit.update'], 'class' => 'col-sm-9','files'=>true])}}

    @include('kgs::admin.business_unit._form')

    {{Form::close()}}
@endsection

