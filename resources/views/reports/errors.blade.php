@extends('layouts.app')

@section('header')
    <div style="
    display: flex;
    width: 100%;
    justify-content: flex-end;
">
{{--        <a href="?excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> {{ t('Excel') }}</a>--}}
        <a href="/reports" class="btn btn-default btn-sm float-left"><i class="fa fa-chevron-left"></i> {{ t('Back') }}</a>
    </div>
@endsection

@section('body')
    <div class="col-md-12">
        @foreach($errors as $error)
            <div class="alert alert-danger text-center">
                <i class="fa fa-times-circle"></i>
                <strong>Oops! Something went wrong! Check the report and try again !</strong>
            </div>
        @endforeach
    </div>

@endsection