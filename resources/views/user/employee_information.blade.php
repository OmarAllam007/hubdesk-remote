@extends('layouts.app')


@section('header')
    <h4 class="pull-left">{{t('My Information')}}</h4>
@endsection

@section('stylesheets')

@endsection
@section('body')
    <div class="flex">
        <div class="w-full">
            <div class="flex-col justify-center items-center">
{{--                <div class="flex  pb-5 justify-center">--}}
{{--                    @foreach($months as $key=>$month)--}}
{{--                        <a class="m-3 w-1/6 text-center py-5  rounded-xl shadow-md  bg-white font-bold--}}
{{--                  @if(request('index') == $key+1) bg-gray-300 shadow-sm @endif"--}}
{{--                           href="{{route('user.information')}}?index={{$key+1}}"--}}

{{--                        >--}}
{{--                            {{$month->format('M')}}</a>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
                <div class="flex justify-center">
                    <div class="w-10"></div>
                    <div class="flex flex-col justify-center p-10 ">
                        @foreach($paths as $path)
                            <iframe src="{{URL::to($path)}}"  height='800' allowfullscreen="" class="pt-5 "></iframe>
                            {{--                        <iframe id="salarySlip" class="w-full pt-5 "--}}
                            {{--                                src="{{route('user.salarySlipPdf')}}?index={{request('index',0)}}" scrolling="auto"--}}
                            {{--                                height="1100"--}}
                            {{--                        ></iframe>--}}
                        @endforeach
                    </div>
                    <div class="w-10"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-col text-center w-full">


    </div>


    {{--    <object data="{{url('storage'.$salarySlip)}}" height="100%" width="100%" type="application/pdf">--}}
    {{--        <iframe src="https://docs.google.com/viewer?url={{url('storage'.$salarySlip)}}&embedded=true"></iframe>--}}
    {{--    </object>--}}


    {{--    <iframe src="https://docs.google.com/viewerng/viewer?url={{url('storage'.$salarySlip)}}&embedded=true" frameborder="0" height="100%" width="100%">--}}
    {{--    </iframe>--}}

@endsection