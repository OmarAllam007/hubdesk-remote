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
        <h3 class=text-center>{{t('Select Item') }}</h3>

        <div class="tiles-container">
            @foreach($subcategory->items as $item)
                <a  class="tile">
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
                            <p class="text-center" style="margin-top: 40px">
                                {{$item->name}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection