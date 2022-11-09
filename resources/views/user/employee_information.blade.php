@extends('layouts.app')


@section('header')
    <h4 class="pull-left">{{t('My Information')}}</h4>
@endsection

@section('stylesheets')

@endsection
@section('body')
    <div class="flex flex-col">
        {{--        <div class="flex justify-center">--}}
        {{--            <div class="w-2/3">--}}
        {{--                <div class="flex justify-end mb-5">--}}
        {{--                    <a href="?pdf" target="_blank" class="bg-transparent hover:bg-green-500 text-green-700 font-semibold--}}
        {{--        hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">--}}
        {{--                        <i class="fa fa-download"></i> Download--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}


        <div class="flex-col w-full text-center">
            <div class="flex bg-white w-3/4 justify-center">
            <a href="{{route('user.information')}}?index=1">Month1</a>
                <a href="{{route('user.information')}}?index=2">Month2</a>

            </div>

            <iframe id="salarySlip"
                    src="{{route('user.salarySlipPdf')}}?index={{request('index',0)}}" scrolling="auto" height="1100" class="w-2/3"></iframe>
        </div>
    </div>

    {{--    <object data="{{url('storage'.$salarySlip)}}" height="100%" width="100%" type="application/pdf">--}}
    {{--        <iframe src="https://docs.google.com/viewer?url={{url('storage'.$salarySlip)}}&embedded=true"></iframe>--}}
    {{--    </object>--}}


    {{--    <iframe src="https://docs.google.com/viewerng/viewer?url={{url('storage'.$salarySlip)}}&embedded=true" frameborder="0" height="100%" width="100%">--}}
    {{--    </iframe>--}}

@endsection