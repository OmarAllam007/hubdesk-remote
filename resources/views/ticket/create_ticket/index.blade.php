@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>

@stop
@section('stylesheets')
    <style>

    </style>
@endsection

@section('body')
    <section class="col-md-12 ">
        <div class=form-group></div>
        <h3 class=text-center>{{t('Select Business Unit') }}</h3>

        <div class="tiles-container">
            @foreach(\App\BusinessUnit::whereHas('categories')->orderBy('name')->get() as $business_unit)
                <a href="{{route('ticket.create.select_category', $business_unit)}}" class="tile">
                    <div class="tile-container" style="display: flex; flex-direction:column;align-items: center;justify-content: center;">
                        {{--<div class="tile-icon" style="">--}}
{{--                            <img src="{{asset('images/logo.png')}}">--}}
                        {{--</div>--}}
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            {{--@if($business_unit->logo)--}}
                            <p class="text-center" style="height: 100px">
                                <img src="{{asset($business_unit->logo)}}" alt="{{asset($business_unit->logo)}}">
                            </p>
                            {{--@endif--}}
                            <p class="text-center" style="margin-top: 40px">
                                {{$business_unit->name}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection