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
                {{--<li class="breadcrumb-item"><a href="#">{{t('Home')}}</a></li>--}}
                <li class="breadcrumb-item">
                    <a href="{{route('ticket.create.select_category',compact('business_unit','category'))}}">
                        {{t($business_unit->name)}}
                    </a>
                </li>
                /
                <li class="breadcrumb-item"> {{t($category->name)}}
                </li>
                {{--<li class="breadcrumb-item"> {{t('Select Subcategory')}}--}}

                {{--</li>--}}
            </ol>
        </nav>
    </div>

    <style>
        @keyframes slideInFromLeft {
            0% {
                transform: translateY(-10%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .card-section {
            animation: .5s ease-out 0s 1 slideInFromLeft;
            padding: 30px;
        }
    </style>
@endsection


@section('body')
    <section class="col-sm-12">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="main">
                    @foreach($category->subcategories()->individual()->orderBy('order')->get() as $subcategory)
                        @if($subcategory->canDisplay(\App\ServiceUserGroup::$SUBCATEGORY))
                            <div class="view view-seventh">
                                <img src="{{$subcategory->logo ? $subcategory->url : '/images/23.png'}}">
                                <div class="info"><p>{{t($subcategory->name)}}</p></div>
                                <div class="mask">
                                    <a href="{{route('ticket.create.select_item', compact('business_unit','subcategory'))}}">
                                        <h2>
                                            {{t($subcategory->name)}}
                                        </h2>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection