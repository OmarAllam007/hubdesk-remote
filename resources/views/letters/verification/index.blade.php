@extends('letters.layout.navbar_less')

@section('header')
    <h4 class="pull-left">{{t('Letter Information')}} - {{$ticketID}}</h4>
    <style>
        #letterView{
            -webkit-overflow-scrolling: touch;
            overflow-y: scroll;
        }
    </style>
@endsection
<body>
@section('body')
    <div class="flex flex-col ">
        <div class="flex justify-center">
            <div class="logo"><a href="{{url('/')}}"><img src="{{asset('images/holding_logo.png')}}"></a></div>
        </div>
        <div class="flex justify-center" id="letterView">

            <iframe
                    src="{{route('letters.generate.pdf',$ticketID)}}"  height="1100"
                    class="w-full  bg-white" style="position:absolute; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"></iframe>
        </div>
    </div>

@endsection

