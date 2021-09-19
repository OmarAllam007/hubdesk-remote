@extends('layouts.app')


@section('body')

    <div class="flex  h-64">
        <div class="flex w-full justify-center m-auto">
            <a class="py-10  w-1/4 text-center font-bold text-2xl
            hover:bg-gray-200 bg-gray-400  hover:text-black hover:shadow-md rounded-xl "
               href="{{route('admin.system_admin')}}">{{t('System Admin')}}</a>

            <div class="px-5 "></div>

            <a class="py-10  w-1/4 text-center font-bold text-2xl hover:bg-gray-200
            bg-gray-400  hover:text-black hover:shadow-md rounded-xl"
               href="{{route('letters.index')}}">{{t('Letters Admin')}}</a>
        </div>
    </div>

@endsection