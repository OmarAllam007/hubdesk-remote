@extends('layouts.app')

@section('header')
    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{route('ticket.create-wizard')}}">
                        {{t('Select Company')}}
                    </a>
                </li>
                /
                <li class="breadcrumb-item">{{t($business_unit->name)}}</li>
            </ol>
        </nav>
    </div>

    <style>
        @keyframes slideInFromLeft {
            0% {
                transform: translateX(-20%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .card-section {
            animation: .5s ease-out 0s 1 slideInFromLeft;
            padding: 30px;
        }

        @keyframes displaySlowly {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }

        }
        {{--.back-animation{--}}
        {{--    background-image: url({{url('/storage'.$business_unit->business_unit_bgd ?? '')}});--}}
        {{--    background-repeat: no-repeat;background-size: cover;--}}
        {{--    animation: .7s ease-in 0s 1 displaySlowly;--}}
        {{--}--}}

    </style>
@endsection


@section('body')
    <div class="col-sm-2"></div>

    <div class="col-sm-8">
        <div class="main">
        @foreach($business_unit->categories()->active()->individual()->ticketType()->orderBy('order')->get() as $category)
            @if($category->canDisplay(\App\ServiceUserGroup::$CATEGORY) && $category->available())
                <!-- SEVENTH EXAMPLE -->
                    <div class="view view-seventh">
                        <img src="{{$category->logo ? $category->logo : '/images/23.png'}}">
                        <div class="info"><p>{{t($category->name)}}</p></div>
                        <div class="mask">
                            <a href="{{route('ticket.create.select_subcategory', compact('business_unit','category'))}}">
                                <h2>{{t($category->name)}}</h2></a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{--        <section class="card-section">--}}

        {{--            <div class=form-group></div>--}}
        {{--            --}}{{--        <h3 class=text-center>{{t('Categories') }}</h3>--}}
        {{--            <div class="row">--}}
        {{--                <div class="col-md-1"></div>--}}
        {{--                <div class="col-md-10">--}}
        {{--                    <div class="tiles-container">--}}
        {{--                        @if(str_contains(strtolower($business_unit->name),'quwa'))--}}

        {{--                            <a href="https://fiori.alkifah.com:5447/sap/bc/ui5_ui5/ui2/ushell/shells/abap/FioriLaunchpad.html?sap-client=900&sap-language=EN&sap-sec_session_created=X"--}}
        {{--                               class="tile" target="_blank">--}}
        {{--                                <div class="tile-container" style="display: flex;--}}
        {{--    flex-direction: column;--}}
        {{--    align-items: center;--}}
        {{--    justify-content: center;--}}
        {{--    height: 250px;--}}
        {{--    width: 250px;">--}}
        {{--                                    --}}{{--<div class="tile-icon" style="">--}}
        {{--                                    --}}{{--                            <img src="{{asset('images/logo.png')}}">--}}
        {{--                                    --}}{{--</div>--}}
        {{--                                    <div class="tile-body"--}}
        {{--                                         style="width: 100%;height: 100%;display: flex; flex-direction:column;">--}}
        {{--                                        --}}{{--@if($business_unit->logo)--}}
        {{--                                        <img src="{{asset('images/fiori-logo.jpeg')}}" style="margin: auto;width: 100%">--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </a>--}}
        {{--                        @endif--}}

        {{--                        @foreach($business_unit->categories()->active()->individual()->ticketType()->orderBy('order')->get() as $category)--}}
        {{--                            @if($category->canDisplay(\App\ServiceUserGroup::$CATEGORY) && $category->available())--}}

        {{--                                <a href="{{route('ticket.create.select_subcategory', compact('business_unit','category'))}}"--}}
        {{--                                   class="tile">--}}
        {{--                                    <div class="tile-container" style="display: flex;--}}
        {{--    flex-direction: column;--}}
        {{--    align-items: center;--}}
        {{--    justify-content: center;--}}
        {{--    height: 250px;--}}
        {{--    width: 250px;">--}}
        {{--                                        <div class="tile-body"--}}
        {{--                                             style="width: 100%;height: 100%;display: flex; flex-direction:column;">--}}
        {{--                                            @if($category->logo && file_exists(storage_path($category->url)))--}}
        {{--                                                <p class="text-center logo-animation" style="height: 100px">--}}
        {{--                                                    <img src="{{$category->url}}" alt="{{$category->url}}">--}}
        {{--                                                </p>--}}


        {{--                                            @else--}}
        {{--                                                <p class="text-center " style="margin:auto">--}}
        {{--                                                    {{t($category->name)}}--}}
        {{--                                                </p>--}}
        {{--                                            @endif--}}
        {{--                                        </div>--}}
        {{--                                    </div>--}}
        {{--                                </a>--}}
        {{--                            @endif--}}
        {{--                        @endforeach--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--                <div class="col-md-1"></div>--}}
        {{--            </div>--}}
        {{--        </section>--}}
    </div>
@endsection