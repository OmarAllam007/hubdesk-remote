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
                            href="{{route('ticket.create.select_category',compact('business_unit'))}}"> {{$item->subcategory->category->name}}
                    </a>
                </li>
                /
                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_subcategory',[$business_unit,$item->subcategory->category])}}"> {{$item->subcategory->name}}
                    </a>
                </li>
                /
                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_item',[$business_unit,$item->subcategory])}}"> {{$item->name}}
                    </a>
                </li>
                {{--                <li class="breadcrumb-item">--}}
                {{--                    {{t('Select SubItem')}}--}}
                {{--                </li>--}}
            </ol>
        </nav>
    </div>
@endsection


@section('body')
    <section class="col-sm-12">

        <div class=form-group></div>
        {{--        <h3 class=text-center>{{t('SubItems') }}</h3>--}}
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="main">

                    @foreach($item->subItems()->individual()->orderBy('order')->get() as $subItem)
                        @if($subItem->canDisplay(\App\ServiceUserGroup::$SUB_ITEM))
                            <div class="view view-seventh">
                                <img src="{{$subItem->logo ? $subItem->url : '/images/23.png'}}">
                                <div class="info"><p>{{t($subItem->name)}}</p></div>
                                <div class="mask">
                                    <a href="{{route('ticket.create-ticket',[
                            $business_unit,$subItem->item->subcategory->category,$subItem->item->subcategory,$subItem->item,$subItem
                            ])}}">
                                        <h2>{{t($subItem->name)}}</h2></a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection