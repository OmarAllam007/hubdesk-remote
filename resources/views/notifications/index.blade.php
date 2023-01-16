@extends('layouts.app')

@section('body')
    <p class="p-3 text-3xl font-bold">{{t('Notifications')}}</p>

    @if($notifications->count())
        <div class="flex">
            <div class="w-full justify-between flex">
                <div class="w-1/3"></div>
                <div class="w-1/3 py-3">
                    <a href="/notifications-mark-all-as-read" class="rounded-xl p-2 bg-white"><i class="fa fa-bell-o"></i> Mark all as read</a>
                </div>
                <div class="w-1/3"><p> </p></div>
            </div>
        </div>
        <div class="flex">
            <div class="w-full">
                <div class="flex justify-center">
                    <ul class="bg-white w-1/3 rounded-xl shadow-sm">
                        @foreach($notifications as $notification)
                            <li class="p-5 flex hover:bg-gray-100 text-2xl @if($notification->notification->read_at) text-gray-600 @else text-black @endif ">
                                <a href="/read-notification/{{$notification->notification->id}}" class="w-full">
                                    <div class="flex-col">
                                        <div class="flex justify-between">
                                            {{$notification->string}}
                                            @if(!$notification->notification->read_at)
                                                <span><i class="fa fa-circle text-sm text-red-600"></i></span>
                                            @endif
                                        </div>
                                        <span class="text-base">{{$notification->notification->created_at->diffForHumans()}}</span>
                                    </div>

                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @include('partials._pagination', ['items' => $notifications])
            </div>
        </div>

    @else
        <div class="flex">
            <div class="w-full">
                <div class="flex justify-center">
                    <p class="rounded-xl p-5 text-2xl bg-orange-300 w-1/2 text-center shadow-md">{{t('No Notifications found')}}
                        !</p>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('javascript')

@endsection
