@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t($business_unit->division->name)}} > {{t($business_unit->name)}} > {{t('Select Category')}}</h4>
    <div class="btn-group">
        {{--@if(Auth::user()->isAdmin())--}}
            <a class="btn btn-outlined  btn-success" href="{{route('kgs.business_documents_folder.index',compact('business_unit'))}}"><i
                    class="fa fa-file"></i> {{t('Folders')}}</a>


            <a class="btn btn-outlined  btn-primary" href="{{route('kgs.document.roles.show',compact('business_unit'))}}"><i
                        class="fa fa-users"></i> {{t('Roles')}}</a>

            <a class="btn btn-danger" href="{{route('kgs.document.manage_notifications',compact('business_unit'))}}"><i
                        class="fa fa-bell"></i> {{t('Notifications')}}</a>
        {{--@endif--}}

        <a class="btn  btn-default" href="{{route('kgs.business_document.select_business_unit',['division'=> $business_unit->division])}}"><i
                    class="fa fa-1x fa-arrow-right"></i></a>
    </div>
@stop

@section('body')
    <div class="flex">
        <div class="flex">
            <a href="{{route('kgs.business_document.select_business_unit', $business_unit->division)}}"
               class=" text-center pt-2 pb-2 pl-5 pr-5  m-5 bg-gray-400 shadow-md  rounded-2xl text-viola "><i
                        class="fa fa-arrow-left"></i>
                {{t('Select Company')}}
            </a>
        </div>
    </div>
    <section class="col-md-12 card-section">
        <div class="tiles-container">
            @foreach(\App\BusinessUnit::find(6)->categories()->corporate()->get() as $category)
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
                                {{t($category->name)}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection