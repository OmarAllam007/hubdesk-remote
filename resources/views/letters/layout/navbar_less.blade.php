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
                <div class="logo"><img src="{{asset('images/h_logo.png')}}"></div>
                {{--                <a href="#" class="navbar-brand">Brand</a>--}}
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

    <footer class="footer">
        <div style="display: flex;">
            <a href="https://hubtech.sa">Powered by Hubdesk from Hubtech</a>
        </div>
        <div>
        </div>
    </footer>
</div>

<script src="{{asset('/js/app.js')}}"></script>
<script src="{{asset('/js/tinymce/tinymce.min.js')}}"></script>


{{--@include('vendor.sweetalert.alert')--}}
@yield('javascript')
</body>
</html>
