@extends('letters.layout.navbar_less')

@section('header')
    <h4 class="pull-left">{{t('Letter Information')}} - {{$ticketDecryptedID}}</h4>
    <style>
        html, body {height:100%; overflow:hidden}
        body {overflow:auto; -webkit-overflow-scrolling:touch}
        /*#letterView{*/
        /*    -webkit-overflow-scrolling: touch;*/
        /*    overflow-y: scroll;*/
        /*}*/
        iframe {
            width: 100% !important;
        }


    </style>

@endsection
<body>
@section('body')
    <div class="flex flex-col ">
        <div class="flex justify-center">
            <div class="logo"><a href="{{url('/')}}"><img src="{{asset('images/holding_logo.png')}}"></a></div>
        </div>


        <div class="flex " id="letterView">
            <div class="w-1/3 "></div>
            <div class="flex flex-col justify-center items-center pt-3  ">

            </div>
            <div class="w-full">
                {!! $view !!}
            </div>

        </div>
    </div>

    <div class="flex justify-center z-50  text-center">
        @php $ticketId  =  Crypt::encryptString($ticketDecryptedID);@endphp
        {!! QrCode::size(120)->generate(route('letters.verify-letter',[$ticketId])); !!}
    </div>
@endsection

