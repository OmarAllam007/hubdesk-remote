@extends('layouts.app')

@section('header')

    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Create Ticket</li>
                <li class="breadcrumb-item">
                    <a href="{{route('ticket.create-wizard')}}">{{$business_unit->name}}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    Select Category
                </li>

            </ol>
        </nav>
    </div>
@endsection


@section('body')
    <section class="col-sm-12">
        <div class="container">

            <div class=form-group></div>
            <h3 class=text-center>{{t('Categories') }}</h3>
            <div class="tiles-container">
                @foreach($business_unit->categories as $category)

                    <a href="{{route('ticket.create.select_subcategory', compact('business_unit','category'))}}"
                       class="tile">
                        <div class="tile-container"
                             style="display: flex;align-items: center;justify-content: center;">
                            {{--<div class="tile-icon" style="">--}}
                            {{--<img src="{{asset('images/logo.png')}}">--}}
                            {{--</div>--}}
                            <div class="tile-body" style="display: flex;flex-direction: column;justify-content: center">
                                <p class="text-center">
                                    {{$category->name}}
                                </p>
                                <p>
                                    {{$category->service_cost ? $category->service_cost : ''}}
                                </p>
                            </div>
                        </div>
                    </a>
                    {{--<p><a href="{{route('category.show', $category)}}" class="btn btn-outlined btn-block btn-primary">{{$category->name}}</a></p>--}}

                @endforeach
            </div>
        </div>

    </section>
@endsection