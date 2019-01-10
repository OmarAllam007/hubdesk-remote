@extends('layouts.app')

@section('header')

    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Create Ticket</a></li>
                <li class="breadcrumb-item"><a href="#">{{$business_unit->name}}
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{route('ticket.create.select_category',compact('business_unit','category'))}}"> {{$category->name}}
                    </a></li>

                <li class="breadcrumb-item"> {{t('Select Subcategory')}}

                </li>
            </ol>
        </nav>
    </div>
@endsection


@section('body')
    <section class="col-sm-12">
        @if ($business_unit->name)
        @endif

        <div class="container">

            <div class=form-group></div>
            <h3 class=text-center>{{t('Subcategories') }}</h3>
            <div class="tiles-container">
                @foreach($category->subcategories as $subcategory)

                    <a href="{{route('ticket.create.select_item', compact('business_unit','category','subcategory'))}}" class="tile">
                        <div class="tile-container"
                             style="display: flex;align-items: center;justify-content: center;">
                            {{--<div class="tile-icon" style="">--}}
                            {{--<img src="{{asset('images/logo.png')}}">--}}
                            {{--</div>--}}
                            <div class="tile-body" style="display: flex;">
                                <p class="text-center">
                                    {{$subcategory->name}}
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