@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>

@stop
@section('stylesheets')
    <style>
        @keyframes slideInFromUp {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
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

    </style>
@endsection

@section('body')
    <section class="col-md-12 card-section">
        <div class=form-group></div>
        <h3 class=text-center>{{t('Select Item') }}</h3>

        <div class="tiles-container">
            @foreach($subcategory->items as $item)
                <a href="{{route('kgs.document.check-requirements',compact('business_unit','category','subcategory','item'))}}"
                   class="tile">
                    <div class="tile-container">
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
                            <p class="text-center" style="margin-top: 40px">
                                {{$item->name}}
                            </p>
                            @if($item->service_cost > 0)
                                <p>
                                    <span>{{$item->service_cost}} SAR</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection