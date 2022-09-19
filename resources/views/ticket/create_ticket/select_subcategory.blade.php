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
                    {{--                    will removed--}}
                    @if(in_array($category->id,[116,160]) )
                        <div class="view view-seventh">
                            <img src="/images/fiori-logo.png">
                            <div class="info"><p>
                                    {{t("Fiori")}}
                                </p></div>
                            <div class="mask">
                                <a target="_blank"
                                   href="https://fiori.alkifah.com:5447/sap/bc/ui5_ui5/ui2/ushell/shells/abap/FioriLaunchpad.html?sap-client=900&sap-language=EN&sap-sec_session_created=X">
                                    <h2>
                                        {{t("Fiori")}}
                                    </h2>
                                </a>
                            </div>
                        </div>

                        <div class="view view-seventh">
                            <!-- /storage/attachments/subcategories/427/44.png -->
                            <img src="{{ asset('images/sf-logo.png') }}">
                            <div class="info"><p>
                                    {{t("Success Factor")}}
                                </p></div>
                            <div class="mask">
                                <a target="_blank"
                                   href="https://performancemanager.successfactors.eu/sf/start?_s.crb=lKkyhwFB1A8TjVql2Pw1KsJNTdBkC1u9L8radVFXdoc%253d#Shell-home">
                                    <h2>
                                        {{t("Success Factor")}}
                                    </h2>
                                </a>
                            </div>
                        </div>
                    @endif

                    @foreach($category->subcategories()->active()->individual()->orderBy('order')->get() as $subcategory)
                        @if($subcategory->canDisplay(\App\ServiceUserGroup::$SUBCATEGORY) && $subcategory->available())
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