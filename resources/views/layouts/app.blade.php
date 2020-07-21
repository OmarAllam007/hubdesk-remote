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

    @if(\Session::get('personlized-language-ar' . auth()->id(), config('app.locale')) == "ar")
        <link rel="stylesheet" href="{{asset('/css/bootstrap-rtl.css')}}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @yield('stylesheets')
    <style>
        ul.navbar > li:hover {
            background: #20639c !important;
            border-radius: 2px;
        }
    </style>

</head>
<body>

<header>
    <nav class="navbar navbar-default navbar-static-top navbar-style exto-bold">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{url('/')}}">
                        <i class="fa fa-life-ring"></i> HUBDESK</a>
                </li>
            </ul>
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

                    @if(auth()->user()->isSupport())
                        <li class="nav-item"><a href="{{route('configurations.index')}}"><i
                                        class="fa fa-cogs"></i> {{t('Configurations')}}</a></li>
                    @endif

                    @can('reports')
                        <li class="nav-item"><a href="{{url('/reports')}}"><i
                                        class="fa fa-bar-chart"></i> {{t('Report')}}</a></li>
                    @endif

                    @can('show_business_document')
                        <li class="nav-item"><a href="{{route('kgs.business_document.select_division')}}"><i
                                        class="fa fa-book"></i> {{t('Business Documents')}}</a></li>

                    @endcan

                    @if (Auth::user()->isAdmin())
                        <li class="nav-item"><a href="{{url('/admin')}}"><i class="fa fa-cogs"></i> {{t('Admin')}}</a>
                        </li>
                    @endif
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
                                    class="fa fa-user"></i> {{Auth::user()->name}} <i class="caret"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('user.reset')}}"><i class="fa fa-unlock "></i> {{t('Reset Password')}}
                                </a></li>
                            <li><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i> {{t('Logout')}}</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </nav>
</header>

<div id="wrapper">
    <main class="container-fluid">
        <div class="row">
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

    <footer>
        <div class="container-fluid">
            <div class="footer-container display-flex">
                <p class="text-mutedtext-right">{{t('Copyright')}} &copy; <a
                            href="http://hubtech.sa">Hubtech</a> {{date('Y')}}</p>

                <p class="text-mutedtext-left" style="font-weight: bold;"><a
                            href="{{asset('attachments/hubdesk-user-guide.pdf')}}" target="_blank"><i
                                class="fa fa-info-circle"></i> {{t('AlQuwa Hubdesk User Guideline')}}</a></p>

            </div>
        </div>
    </footer>
</div>

<script src="{{asset('/js/app.js')}}"></script>
<script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>


@include('vendor.sweetalert.alert')
@yield('javascript')

</body>
</html>
