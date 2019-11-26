@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>

@stop
@section('stylesheets')
    <style>
        @keyframes slideInFromUp {
            0% {
                transform: translateY(-20%);
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
        <div class=form-group></div>
{{--        <h3 class=text-center>{{t('Select Subcategory') }}</h3>--}}

        <div class="tiles-container">
            @foreach($category->subcategories as $subcategory)
                <a href="{{route('kgs.document.select_item', compact('business_unit','category','subcategory'))}}" class="tile">
                    <div class="tile-container" >
                        {{--<div class="tile-icon" style="">--}}
                        {{--                            <img src="{{asset('images/logo.png')}}">--}}
                        {{--</div>--}}
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            {{--@if($category->logo)--}}
                            <p class="text-center" style="height: 25px;">
                                {{--@if($category->logo)--}}
                                {{--<img src="{{asset($category->logo)}}" alt="{{asset($category->logo)}}">--}}
                                {{--@else--}}
                                {{--@endif--}}
                            </p>
                            <p class="text-center" style="margin-top: 20px">
                                {{t($subcategory->name)}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection