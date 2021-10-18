@extends('layouts.app')

{{--@section('header')--}}
{{--    <h4 class="pull-left">{{t('Business Units')}}</h4>--}}
{{--@stop--}}
@section('stylesheets')

    <style>

    </style>
@endsection

@section('body')
    {{--    <section class="col-md-12 card-section">--}}
    {{--        <div class=form-group></div>--}}
    <div class="flex justify-center mt-24 flex-wrap ">
        @foreach(\App\BusinessUnit::whereHas('categories')->orderBy('name')->get() as $business_unit)
            @if($business_unit->canDisplay())
                <a href="{{route('ticket.create.select_category', $business_unit)}}"
                   class="w-1/2  lg:w-1/5 md:w-1/4  xl:w-1/5
                        p-5 m-5  bg-white rounded-xl hover:shadow-lg shadow-sm   animate__animated animate__flipInY">
                    @if($business_unit->logo)
                        <p class="text-center ">
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
                        <p class="text-center  text-4xl font-extrabold text-viola animate__animated animate__lightSpeedInLeft">
                            نعين ونعاون
                        </p>
                    @endif


                </a>
            @endif
        @endforeach
    </div>

    {{--    </section>--}}
@endsection