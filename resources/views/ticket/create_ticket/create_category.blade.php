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

                <li class="breadcrumb-item">
                    {{t($business_unit->name)}}
                </li>
                {{--<li class="breadcrumb-item">--}}
                    {{--{{t('Select Category')}}--}}
                {{--</li>--}}

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
    </style>
@endsection


@section('body')
    <section class="col-sm-12 card-section">

        <div class=form-group></div>
{{--        <h3 class=text-center>{{t('Categories') }}</h3>--}}
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="tiles-container">
                    @if(str_contains(strtolower($business_unit->name),'quwa'))
                        <a target="_blank"
                           href="https://fiori.alkifah.com:5447/sap/bc/ui5_ui5/ui2/ushell/shells/abap/FioriLaunchpad.html?sap-sec_session_created=X&sap-sec_session_created=X"
                           class="tile">
                            <div class="tile-container"
                                 style="display: flex;align-items: center;justify-content: center;">
                                <div class="tile-body"
                                     style="display: flex;flex-direction: column;justify-content: center">
                                    <img src="{{asset('images/fiori-logo.jpeg')}}">
                                    {{--<p class="text-center">--}}
                                    {{--{{t($category->name)}}--}}
                                    {{--</p>--}}
                                    {{--<p>--}}
                                    {{--{{$category->service_cost ? $category->service_cost : ''}}--}}
                                    {{--</p>--}}
                                </div>
                            </div>
                        </a>
                    @endif

                    @foreach($business_unit->categories as $category)
                        <a href="{{route('ticket.create.select_subcategory', compact('business_unit','category'))}}"
                           class="tile">
                            <div class="tile-container"
                                 style="display: flex;align-items: center;justify-content: center;">
                                <div class="tile-body"
                                     style="display: flex;flex-direction: column;justify-content: center">
                                    <p class="text-center">
                                        {{t($category->name)}}
                                    </p>
                                    <p>
                                        {{$category->service_cost ? $category->service_cost : ''}}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

    </section>
@endsection