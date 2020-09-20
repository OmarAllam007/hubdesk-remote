@extends('layouts.app')

@section('header')
    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{t('Create Ticket')}}</a></li>
                /
                <li class="breadcrumb-item"><a href="#">{{$business_unit->name}}
                    </a>
                </li>
                /
                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_category',compact('business_unit'))}}"> {{$subcategory->category->name}}
                    </a>
                </li>
                /
                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_subcategory',[$business_unit,$subcategory->category])}}"> {{$subcategory->name}}
                    </a>
                </li>
                {{--                <li class="breadcrumb-item">--}}
                {{--                    {{t('Select Service')}}--}}
                {{--                </li>--}}
            </ol>
        </nav>
    </div>
@endsection


@section('body')
    <section class="col-sm-12">

        <div class=form-group></div>
        {{--            <h3 class=text-center>{{t('Items') }}</h3>--}}
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="main">
                    @foreach($subcategory->items()->individual()->orderBy('order')->get() as $item)
                        @if($item->canDisplay(\App\ServiceUserGroup::$ITEM))
                            <div class="view view-seventh">
                                <img src="{{$item->logo ? $item->url : '/images/23.png'}}">
                                <div class="info"><p>{{$item->name}}</p></div>
                                <div class="mask">
                                    <a href="{{route('ticket.create.select_subItem', compact('business_unit','item'))}}">
                                        <h2>{{$item->name}}</h2></a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>


    </section>
@endsection