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
    <div class="flex">
        <div class="flex">
            <a href="{{route('ticket.create-wizard')}}"
                    class=" text-center pt-2 pb-2 pl-5 pr-5  m-5 bg-gray-400 shadow-md  rounded-2xl text-viola "><i
                        class="fa fa-arrow-left"></i>
                {{t('Select Company')}}
            </a>
        </div>
    </div>

    <div class="col-sm-2"></div>

    <div class="col-sm-8">
        <div class="main">
            @if($business_unit->id == 6)
                @can('show_business_document')
                    <div class="view view-seventh">
                        <img src="/images/23.png">
                        <div class="info"><p>{{t('Government relation services')}}</p></div>
                        <div class="mask">
                            <a href="{{route('kgs.business_document.select_division')}}">
                                <h2>{{t('Government relation services')}}</h2></a>
                        </div>
                    </div>
                @endcan
            @endif

            @foreach($business_unit->categories()->active()->individual()->ticketType()->orderBy('order')->get() as $category)
                @if($category->canDisplay(\App\ServiceUserGroup::$CATEGORY) && $category->available())
                <!-- SEVENTH EXAMPLE -->
                    <div class="view view-seventh">
                        <img src="{{$category->logo ? $category->url : '/images/23.png'}}">
                        <div class="info"><p>{{t($category->name)}}</p></div>
                        <div class="mask">
                            <a href="{{route('ticket.create.select_subcategory', compact('business_unit','category'))}}">
                                <h2>{{t($category->name)}}</h2></a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection