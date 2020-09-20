@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>
@stop
@section('stylesheets')

    <style>

    </style>
@endsection

@section('body')
    <section class="col-md-12 card-section">
        <div class=form-group></div>
        <div class="tiles-container">
            @foreach(\App\BusinessUnit::whereHas('categories')->orderBy('name')->get() as $business_unit)
                @if($business_unit->canDisplay())
                    <a href="{{route('ticket.create.select_category', $business_unit)}}" class="tile">
                        <div class="tile-container" style="display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 250px;
    width: 250px;">
                            {{--<div class="tile-icon" style="">--}}
                            {{--                            <img src="{{asset('images/logo.png')}}">--}}
                            {{--</div>--}}
                            <div class="tile-body"
                                 style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                                @if($business_unit->logo)
                                    <p class="text-center logo-animation">
                                        <img src="{{$business_unit->url}}" alt="{{$business_unit->url}}">
                                    </p>
                                    {{--@endif--}}
                                @else
                                    <p class="text-center " style="margin-top: 20px;">
                                        {{$business_unit->name}}
                                    </p>
                                @endif
                                {{--for demonistration only--}}
                                @if($business_unit->id == 10)
                                    <p class="text-center quot-animation">
                                        نعين ونعاون
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

    </section>
@endsection