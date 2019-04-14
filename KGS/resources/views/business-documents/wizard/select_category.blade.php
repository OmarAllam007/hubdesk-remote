@extends('layouts.app')

@section('header')
    <h4 class="pull-left"> {{$business_unit->name}} > {{t('Select Category')}}</h4>
    <div class="btn-group">
        <a class="btn  btn-success" href="{{route('kgs.document.index',compact('business_unit'))}}"><i
                    class="fa fa-file"></i> {{t('Documents')}}</a>

        @if(Auth::user()->isAdmin())
            <a class="btn  btn-info" href="{{route('kgs.document.roles.show',compact('business_unit'))}}"><i
                        class="fa fa-users"></i> {{t('Roles')}}</a>
        @endif

        <a class="btn  btn-default" href="{{route('kgs.business_document',compact('business_unit'))}}"><i
                    class="fa fa-1x fa-arrow-right"></i></a>
    </div>
@stop
@section('stylesheets')
    <style>

    </style>
@endsection

@section('body')
    <section class="col-md-12 ">
        <div class="tiles-container">
            @foreach(\App\BusinessUnit::find(env('GS_ID'))->categories as $category)
                <a href="{{route('kgs.document.select_subcategory',compact('business_unit','category'))}}" class="tile">
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
                                {{$category->name}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection