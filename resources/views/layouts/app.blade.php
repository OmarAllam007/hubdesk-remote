<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HubDesk</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mada:400,700">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">--}}
    <link rel="stylesheet" href="{{asset('/css/app.css')}}?random">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}?random">


    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @yield('stylesheets')

    @if(isset($business_unit))
        <style>
            body {
                background: #f9f9f9 url(../images/white_texture.png) repeat top left;
                background-image: url({{url('/storage'.$business_unit->business_unit_bgd ?? '')}});
                background-position: top center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                color: #333;
                /*font-family: 'Esphimere Regular', Arial, sans-serif;*/
                font-size: 13px;
            }
        </style>
    @endif

    <style>
        *:not(i), .quot-animation {
            font-family: 'Exo2-Plain Font', Arial !important;
        }
    </style>
    @if(\Session::get('personalized-language' . auth()->id(), config('app.locale')) == "ar")
        <style>
            *:not(i) {
                font-family: "Sans-Plain Font", Arial !important;
            }
        </style>
    @endif
    <style>


    </style>
</head>
<body>


<header>
    <nav class="navbar navbar-default navbar-static-top navbar-style exto-bold">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="logo"><a href="{{url('/')}}"><img src="{{asset('images/h_logo.png')}}"></a></div>
                {{--                <a href="#" class="navbar-brand">Brand</a>--}}
            </div>
            <div id="navbarCollapse" class="collapse navbar-collapse">

                @if (!\Auth::guest())
                    <ul class="nav navbar-nav ">
                        {{--@if(Auth::user()->isAdmin())--}}
                        <li class="nav-item"><a href="{{route('ticket.create')}}"><i
                                        class="fa fa-plus"></i> {{t('New Ticket')}}</a></li>
                        {{--@endif--}}
                        <li class="nav-item"><a href="{{route('ticket.index')}}"><i
                                        class="fa fa-ticket"></i> {{t('Tickets')}}</a></li>

                        @can('dashboard')
                            <li class="nav-item"><a href="{{route('dashboard.select_business_unit')}}"><i
                                            class="fa fa-dashboard"></i> {{t('Dashboard')}}</a></li>
                        @endcan



                        @can('reports')
                            <li class="nav-item"><a href="{{url('/reports')}}"><i
                                            class="fa fa-bar-chart"></i> {{t('Report')}}</a></li>
                        @endif

                        @can('show_business_document')
                            <li class="nav-item"><a href="{{route('kgs.business_document.select_division')}}"><i
                                            class="fa fa-book"></i> {{t('Corporate Services')}}</a></li>

                        @endcan

                    </ul>


                    <ul class="nav navbar-nav">
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-language"></i>
                                <i class="caret"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('site.changeLanguage','ar')}}"> {{t('Arabic')}}</a></li>
                                <li><a href="{{route('site.changeLanguage','en')}}"> {{t('English')}}</a></li>
                                <li><a href="{{route('site.changeLanguage','in')}}"> {{t('Indian')}}</a></li>
                                <li><a href="{{route('site.changeLanguage','ur')}}"> {{t('URDU')}}</a></li>
                                <li><a href="{{route('site.changeLanguage','nep')}}"> {{t('Nepali')}}</a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav
                    @if(\Session::get('personlized-language-ar' . \Auth::user()->id, \Config::get('app.locale'))=="ar")
                            navbar-left @else navbar-right
                    @endif">
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                        class="fa fa-user"></i> {{Auth::user()->profile_name}} <i class="caret"></i></a>
                            <ul class="dropdown-menu">
                                @if (Auth::user()->isAdmin())
                                    <li><a href="{{url('/admin')}}"><i class="fa fa-cogs"></i> {{t('Admin')}}
                                        </a>
                                    </li>
                                @endif
                                <li><a href="{{route('user.reset')}}"><i
                                                class="fa fa-unlock "></i> {{t('Reset Password')}}
                                    </a></li>
                                @if(auth()->user()->isSupport())
                                    <li><a href="{{route('configurations.index')}}"><i
                                                    class="fa fa-cogs"></i> {{t('Configurations')}}</a></li>
                                @endif
                                <li><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i> {{t('Logout')}}</a></li>
                            </ul>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>
</header>

<div id="wrapper">
    <main class="container-fluid">
        <div class="row back-animation">
            <div class="title-bar">
                <div class="container-fluid title-container">
                    @yield('header')
                </div>
            </div>
            @hasSection('sidebar')
                @yield('sidebar')
            @endif

            @yield('body')
            @include('sweetalert::alert')
        </div>
    </main>

    {{--    <footer>--}}
    {{--        <div class="container-fluid">--}}
    {{--            <div class="footer-container display-flex">--}}
    {{--                <p class="text-mutedtext-right">{{t('Copyright')}} &copy; <a--}}
    {{--                            href="http://hubtech.sa">Hubtech</a> {{date('Y')}}</p>--}}

    {{--                <p class="text-mutedtext-left" style="font-weight: bold;"><a--}}
    {{--                            href="{{asset('attachments/hubdesk-user-guide.pdf')}}" target="_blank"><i--}}
    {{--                                class="fa fa-info-circle"></i> {{t('AlQuwa Hubdesk User Guideline')}}</a></p>--}}

    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </footer>--}}


    <footer class="footer">
        <div style="display: flex;">
{{--            <a href="#"><i class="fa fa-envelope"></i>cs.alquwa@alkifah.com</a>--}}
            <a href="https://hubtech.sa">Powered by Hubdesk from Hubtech</a>
        </div>
        <div>
            {{--<a
                    href="{{asset('attachments/hubdesk-user-guide.pdf')}}" target="_blank"><i
                        class="fa fa-download"></i> {{t('AlQuwa Hubdesk User Guideline')}}</a>--}}
        </div>
    </footer>
</div>

<script src="{{asset('/js/app.js')}}"></script>
<script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>


@include('vendor.sweetalert.alert')
@yield('javascript')

</body>
</html>
