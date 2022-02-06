@extends('letters.layout.navbar_less')

@section('header')
    <h4 class="pull-left">{{t('Letter Information')}} - {{$ticketID}}</h4>
@endsection
<body>
@section('body')
    <div class="flex flex-col ">
        <div class="flex justify-center">
            <div class="logo"><a href="{{url('/')}}"><img src="{{asset('images/holding_logo.png')}}"></a></div>
        </div>
        <div class="flex justify-center">

            <iframe id="salarySlip"
                    src="{{route('letters.generate.pdf',$ticketID)}}" scrolling="auto" height="1100"
                    class="w-2/3 bg-white"></iframe>
        </div>
    </div>

@endsection

