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
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    {{--    ?version={{time()}}--}}
    {{--    ?version={{time()}}--}}
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
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
        @font-face {
            font-family: englishFont;
            src: url('{{ asset('/fonts/english_font.ttf') }}');
        }

        *:not(i), .quot-animation {
            font-family: 'englishFont', Arial !important;
        }
    </style>

    @if(\Session::get('personalized-language' . auth()->id(), config('app.locale')) == "ar")
        <style>
            @font-face {
                font-family: arabic;
                src: url('{{ asset('/fonts/arabic.otf') }}');

            }

            *:not(i) {
                font-family: "arabic", Arial !important;
            }
        </style>
    @endif
</head>
<body style="background-color: #eaeaea">


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
                        {{--                        --}}
                        @if(auth()->user()->employee_id)
                            <li class="nav-item"><a href="{{route('user.information')}}"><i
                                            class="fa fa-user-circle"></i> {{t('My Information')}}</a></li>
                        @endif

                        @can('dashboard')
                            <li class="nav-item"><a href="{{route('dashboard.select_business_unit')}}"><i
                                            class="fa fa-dashboard"></i> {{t('Dashboard')}}</a></li>
                        @endcan


                        @can('reports')
                            <li class="nav-item"><a href="{{url('/reports')}}"><i
                                            class="fa fa-bar-chart"></i> {{t('Report')}}</a></li>
                        @endif

                        @can('e_card_admin')
                            <li class="nav-item"><a href="{{url('/e-card/admin/index')}}"><i
                                            class="fa fa-qrcode"></i> {{t('E-Card Admin')}}</a></li>
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

                    <ul class="nav navbar-nav navbar-right" style="border: none;">
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell"></i><span
                                        class="px-2  bg-red-700 rounded-full">{{auth()->user()->unreadNotifications->count()}}</span>
                            </a>

                            <ul class="dropdown-menu rounded " style="padding: 0 !important;">
                                @if(auth()->user()->unreadNotifications->count())
                                    @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                                        @php
                                            $userNotification = new \App\Helpers\UserNotification($notification);
                                        @endphp
                                        <li class="flex hover:bg-gray-200" style="padding: 0 !important;">
                                            <a href="/read-notification/{{$userNotification->notification->id}}"
                                               class="w-full p-0  justify-between p-5"
                                               style="padding: 0 !important;">
                                                <div class="flex w-full justify-between">
                                                    <span class="px-2 pt-3 text-gray-700 normal-case overflow-hidden">{{$userNotification->string}}</span>
                                                    <span class="text-base p-2 text-left justify-end">{{$userNotification->notification->created_at->diffForHumans()}}</span>

                                                </div>
                                            </a>
                                        </li>
                                    @endforeach

                                @else
                                    <li class="hover:bg-gray-100 " style="padding: 0 !important;">
                                        <a href="#" class="p-0 " style="padding: 0 !important;">
                                            <p class="px-5 py-3 text-gray-700 text-xl text-center normal-case"
                                               style="padding: 0"> <span class="p-3">
                                                    {{t('No unread notifications found!')}}
                                                </span></p>
                                        </a>
                                    </li>
                                @endif
                                <li class="hover:bg-gray-100" style="padding: 0 !important;">
                                    <a href="/notifications" class="p-0 " style="padding: 0 !important;">
                                        <p class="px-2 py-3 text-gray-700 text-xl text-center"
                                           style="padding: 0"> {{t('View all notifications')}}</p>
                                    </a>
                                </li>
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

            @hasSection('header')
                <div class="title-bar">
                    <div class="container-fluid title-container">
                        @yield('header')
                    </div>
                </div>
            @endif

            <div class="flex">
                @hasSection('sidebar')
                    <div class="w-1/4">
                        @yield('sidebar')
                    </div>
                @endif

                @hasSection('sidebar')
                    <div class="w-3/4">
                        @else
                            <div class="w-full">
                                @endif

                                @yield('body')
                                @hasSection('sidebar')
                            </div>
                            @else
                            @endif

                    </div>

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
