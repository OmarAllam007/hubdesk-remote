@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>


@endsection
@section('stylesheets')
    <style>
        @keyframes slideInFromUp {
            0% {
                transform: translateX(20%);
            }
            100% {
                transform: translateX(0);
            }
        }

        @keyframes slideInFromRight {
            0% {
                transform: translateX(5%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .card-section {
            animation: .5s ease-out 0s 1 slideInFromUp;
            padding: 30px;
        }
        .logo-animation {
            animation: 1.5s ease-out 0s 1 slideInFromRight;
        }

        .quot-animation{
            animation: 1.5s ease-out 0s 1 slideInFromLeft;
        }
        p.quot-animation{
            margin-top: 80px;
            font-size: 18pt;
            font-weight: 800;
        }
    </style>
@endsection

@section('body')
    <section class="col-md-12 card-section">
{{--        <h3 class=text-center>{{t('Select Business Unit') }}</h3>--}}

        <div class="tiles-container">
            @foreach(\App\BusinessUnit::orderBy('name')->get() as $business_unit)
                @can('show_business_unit',$business_unit)
                <a href="{{route('kgs.document.select_category', compact('business_unit'))}}" class="tile">
                    <div class="tile-container" >
                        {{--<div class="tile-icon" style="">--}}
                        {{--                            <img src="{{asset('images/logo.png')}}">--}}
                        {{--</div>--}}
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            {{--@if($business_unit->logo)--}}
                            <p class="text-center" @if($business_unit->logo) style="height: 100px" @else style="height: 25px" @endif>
                                @if($business_unit->logo)
                                <img src="{{asset($business_unit->url)}}" alt="{{asset($business_unit->url)}}">
                                @else
                                @endif
                            </p>
                            <p class="text-center" style="margin-top: 40px">
                                {{$business_unit->name}}
                            </p>
                        </div>
                    </div>
                </a>
                @endcan
            @endforeach
        </div>

    </section>
@endsection