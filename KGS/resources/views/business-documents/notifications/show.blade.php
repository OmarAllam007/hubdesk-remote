@extends('layouts.app')
@section('header')
    <h4 class="pull-left"> {{$business_unit->name}} > {{t('Manage Notifications')}}</h4>
    <div class="btn-group">
        {{--        <a class="btn  btn-success" href="{{route('kgs.document.index',compact('business_unit'))}}"><i class="fa fa-file" ></i> {{t('Documents')}}</a>--}}
        <a class="btn  btn-default" href="{{route('kgs.document.select_category',compact('business_unit'))}}"><i
                    class="fa fa-1x fa-arrow-right"></i></a>
    </div>
@stop
@section('body')
    @php
    if($business_unit->document_notifications->count()){
        $first_notification = $business_unit->document_notifications[0];
        $second_notification = $business_unit->document_notifications[1];
    }
    @endphp
    {{ Form::open(['route' => ['kgs.document.manage_notifications','business_unit'=>$business_unit], 'files' => true, 'class' => 'col-sm-6']) }}
    {{ csrf_field() }}

    <div id="TicketForm">
        <fieldset>
            <legend>
                {{t('First Notification')}}
            </legend>

            <div class="form-group">
                {{ Form::label('notification', t('Days before end date'), ['class' => 'control-label']) }}
                {{ Form::text("notifications[1][period]", isset($first_notification) ? $first_notification->days : 0, ['class' => 'form-control','multiple'=>'true']) }}
            </div>
            <div class="form-group">
                {{ Form::label('users', t('Notified Users'), ['class' => 'control-label']) }}
                {{ Form::select("notifications[1][users][]", \App\User::all()->pluck('name','id')->toArray(),isset($first_notification) ? $first_notification->users : null, ['class' => 'form-control select2','multiple'=>'true']) }}

                @if ($errors->has('users'))
                    <div class="error-message">{{$errors->first('users')}}</div>
                @endif
            </div>
        </fieldset>

        <fieldset>
            <legend>
                {{t('Second Notification')}}
            </legend>

            <div class="form-group">
                {{ Form::label('users', t('Days before end date'), ['class' => 'control-label']) }}
                {{ Form::text("notifications[2][period]", isset($second_notification) ? $second_notification->days : 0, ['class' => 'form-control','multiple'=>'true']) }}

            </div>
            <div class="form-group">
                {{ Form::label('users', t('Notified Users'), ['class' => 'control-label']) }}
                {{ Form::select("notifications[2][users][]", \App\User::all()->pluck('name','id')->toArray(),isset($second_notification) ? $second_notification->users : null, ['class' => 'form-control select2','multiple'=>'true']) }}

                @if ($errors->has('users'))
                    <div class="error-message">{{$errors->first('users')}}</div>
                @endif
            </div>
        </fieldset>


        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <button class="btn btn-success"><i class="fa fa-check"></i> {{t('Submit')}}</button>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection
@section('javascript')

@append