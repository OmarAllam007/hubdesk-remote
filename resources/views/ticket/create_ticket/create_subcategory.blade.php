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
                {{--<li class="breadcrumb-item"><a href="#">{{t('Home')}}</a></li>--}}
                <li class="breadcrumb-item"><a href="{{route('ticket.create-wizard')}}">
                        <a href="{{route('ticket.create.select_category',compact('business_unit','category'))}}">
                        {{t($business_unit->name)}}
                    </a>
                </li>
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
    <section class="col-sm-12 card-section">
        @if ($business_unit->name)
        @endif

            <div class=form-group></div>
{{--            <h3 class=text-center>{{t('Subcategories') }}</h3>--}}
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="tiles-container">
                        @foreach($category->subcategories as $subcategory)
                            @if($subcategory->canDisplay(\App\ServiceUserGroup::$SUBCATEGORY))
                            <a href="{{route('ticket.create.select_item', compact('business_unit','category','subcategory'))}}" class="tile">
                                <div class="tile-container"
                                     style="display: flex;align-items: center;justify-content: center;">
                                    {{--<div class="tile-icon" style="">--}}
                                    {{--<img src="{{asset('images/logo.png')}}">--}}
                                    {{--</div>--}}
                                    <div class="tile-body" style="display: flex;">
                                        <p class="text-center">
                                            {{t($subcategory->name)}}
                                        </p>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{--<p><a href="{{route('category.show', $category)}}" class="btn btn-outlined btn-block btn-primary">{{$category->name}}</a></p>--}}

                        @endforeach
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
    </section>
@endsection