@extends('layouts.app')

@section('body')
<div class="flex w-full pt-10">
    <div class="flex  justify-center">
        <div class="p-5 m-5 rounded-2xl bg-orange-300 shadow-lg   w-1/2 ">
            <strong class="text-2xl flex justify-center ">
                <i class="fa fa-lock pl-2  pr-2 pt-1 "></i>
                {{ t('You are not authorized') }}
            </strong>
        </div>
    </div>
</div>
@endsection
