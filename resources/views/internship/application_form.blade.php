@extends('layouts.app')

@section('body')
    <div class="flex justify-center">
        <div class="w-full flex-col">
            <div class="flex justify-center">
                <div class="w-1/2">
                    <p class="flex justify-center p-5 m-5 bg-viola
                text-white text-3xl rounded-3xl shadow-md">
                        {{t('Internship Request Form')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @if(session()->has('request_send'))
        <div class="flex ">
            <div class="w-full">
                <p class="p-5 m-5 bg-success font-bold text-center text-2xl shadow-lg rounded-lg ">
                    Your request has been created successfully.
                </p>
            </div>
    </div>
    @endif
    <form class="w-full m-5" method="post" action="{{route('internship.post')}}" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col  flex-wrap -mx-3 mb-6">
            @include('internship._personal')
            <p class="py-5 "></p>
            @include('internship._internship')
            <p class="py-5 "></p>
            @include('internship._files')
            <p class="py-5 "></p>
            @include('internship._questions')
        </div>
        <div class="flex">
            <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                {{t('Submit')}}
            </button>
        </div>
    </form>
@endsection