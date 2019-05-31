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

        @keyframes slideInFromLeft {
            0% {
                transform: translateX(-20%);
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

        <div class="tiles-container">
            @foreach(\App\BusinessUnit::whereHas('categories')->orderBy('name')->get() as $business_unit)
                <a href="{{route('ticket.create.select_category', $business_unit)}}" class="tile" >
                    <div class="tile-container" style="display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 250px;
    width: 250px;">
                        {{--<div class="tile-icon" style="">--}}
{{--                            <img src="{{asset('images/logo.png')}}">--}}
                        {{--</div>--}}
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            {{--@if($business_unit->logo)--}}
                            <p class="text-center logo-animation" style="height: 100px">
                                <img src="{{$business_unit->url}}" alt="{{$business_unit->url}}">
                            </p>
                            {{--@endif--}}
                            @if(!$business_unit->logo)
                            <p class="text-center " style="margin-top: 20px;">
                                {{$business_unit->name}}
                            </p>
                            @endif
                            {{--for demonistration only--}}
                            @if(str_contains(strtolower($business_unit->name),'quwa'))
                            <p class="text-center quot-animation" >
                                نعين ونعاون
                            </p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection