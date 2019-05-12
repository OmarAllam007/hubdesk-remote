@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Reset Password')}}</h4>
    <style>
        .form-row {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1px
        }
    </style>
@stop

@section('body')
    <div class="form-row">
        {{ Form::open(['route' => 'user.reset', 'class' => 'col-sm-6 auth-form']) }}

        <div class="form-group col-md-12 {{$errors->has('old_password')? 'has-error' : ''}}">
            {{ Form::label('old_password', t('Old Password'), ['class' => 'control-label']) }}
            {{ Form::password('old_password', ['class' => 'form-control']) }}
            @if ($errors->has('old_password'))
                <div class="error-message">{{$errors->first('old_password')}}</div>
            @endif
        </div>

        <div class="form-group col-md-12 {{$errors->has('password')? 'has-error' : ''}}">
            {{ Form::label('password', t('New Password'), ['class' => 'control-label']) }}
            {{ Form::password('password', ['class' => 'form-control']) }}
            @if ($errors->has('password'))
                <div class="error-message">{{$errors->first('password')}}</div>
            @endif
        </div>

        <div class="form-group col-md-12 {{$errors->has('password')? 'has-error' : ''}}">
            {{ Form::label('password_confirmation', t('Confirm Password'), ['class' => 'control-label']) }}
            {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
            @if ($errors->has('password_confirmation'))
                <div class="error-message">{{$errors->first('password_confirmation')}}</div>
            @endif
        </div>

        <div class="form-group col-md-12 form-row">
            <button class="btn btn-success btn-rounded col-md-6">{{t('Submit')}}</button>
        </div>
        {{ Form::close() }}
    </div>

@endsection
