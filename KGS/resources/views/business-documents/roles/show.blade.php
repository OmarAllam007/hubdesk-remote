@extends('layouts.app')
@section('header')
    <h4 class="pull-left"> {{$business_unit->name}} > {{t('Authorized Users')}}</h4>
    <div class="btn-group">
        {{--        <a class="btn  btn-success" href="{{route('kgs.document.index',compact('business_unit'))}}"><i class="fa fa-file" ></i> {{t('Documents')}}</a>--}}
        <a class="btn  btn-default" href="{{route('kgs.document.select_category',compact('business_unit'))}}"><i
                    class="fa fa-1x fa-arrow-right"></i></a>
    </div>
@stop
@section('body')
    {{ Form::open(['route' => ['kgs.document.roles.submit','business_unit'=>$business_unit], 'files' => true, 'class' => 'col-sm-6']) }}

    {{ csrf_field() }}
    <div id="TicketForm">
        <div class="form-group form-group-sm {{$errors->has('users')? 'has-error' : ''}}">
            {{ Form::label('users', t('Authorized Users'), ['class' => 'control-label']) }}
            {{ Form::select('users[]', \App\User::orderBy('name')->pluck('name','id'),$business_unit->document_roles, ['class' => 'form-control select2','multiple'=>'true']) }}

            @if ($errors->has('users'))
                <div class="error-message">{{$errors->first('users')}}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection
@section('javascript')

@append