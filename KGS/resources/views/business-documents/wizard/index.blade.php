@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>


@endsection
@section('stylesheets')
    <style>

    </style>
@endsection

@section('body')
    <section class="col-md-12 ">
        <h3 class=text-center>{{t('Select Business Unit') }}</h3>

        <div class="tiles-container">
            @foreach(\App\BusinessUnit::orderBy('name')->get() as $business_unit)
                <a href="{{route('kgs.document.select_category', compact('business_unit'))}}" class="tile">
                    <div class="tile-container" >
                        {{--<div class="tile-icon" style="">--}}
                        {{--                            <img src="{{asset('images/logo.png')}}">--}}
                        {{--</div>--}}
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            {{--@if($business_unit->logo)--}}
                            <p class="text-center" @if($business_unit->logo) style="height: 100px" @else style="height: 25px" @endif>
                                @if($business_unit->logo)
                                <img src="{{asset($business_unit->logo)}}" alt="{{asset($business_unit->logo)}}">
                                @else
                                @endif
                            </p>
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