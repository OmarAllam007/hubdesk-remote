@extends('layouts.app')

@section('header')
    <h4 class="flex" style="margin-bottom: 0">Processing Error</h4>
@stop

@section('body')
    <div class="flex justify-center pt-10">
        <div class="bg-orange-200  text-center rounded-xl shadow-lg w-1/2 ">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>

            <p><i class="fa fa-exclamation-circle fa-4x"></i></p>

            <p class="text-2xl pt-10 "><strong>No Salary Slip found to display,
                    kindly contact your HR department</strong></p>

            <p>&nbsp;</p>

            <p class="text-xl"><strong>
                    URL: <a href="{{request()->getUri()}}">{{ request()->getUri() }}</a></strong></p>

            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>


        </div>
    </div>
@stop