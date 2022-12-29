@extends('layouts.app')


@section('header')
    <h4 class="pull-left">{{t('My Information')}}</h4>
@endsection

@section('stylesheets')

@endsection
@section('body')
    <div class="flex" id="salarySlip">
        <salary-slip class="w-full"></salary-slip>
    </div>


@endsection

@section('javascript')
    <script src="{{asset('/js/salary_slip/index.js')}}"></script>
@endsection
