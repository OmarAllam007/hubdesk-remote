@extends('layouts.app')

@section('header')

    <div class="display-flex">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{t('Create Ticket')}}</a></li>

                <li class="breadcrumb-item"><a href="#">{{$business_unit->name}}
                    </a>
                </li>

                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_category',compact('business_unit'))}}"> {{$item->subcategory->category->name}}
                    </a>
                </li>

                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_subcategory',[$business_unit,$item->subcategory->category])}}"> {{$item->subcategory->name}}
                    </a>
                </li>

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
                <div class="tiles-container">
                    @foreach($item->subItems()->individual()->orderBy('order')->get() as $subItem)
                        @if($subItem->canDisplay(\App\ServiceUserGroup::$SUB_ITEM))

                            <a href="{{route('ticket.create-ticket',[
                            $business_unit,$subItem->item->subcategory->category,$subItem->item->subcategory,$subItem->item,$subItem
                            ])}}"
                               class="tile">
                                <div class="tile-container"
                                >
                                    <div class="tile-body" style="display: flex; flex-direction:column; width: 100%;height: 100%;">
                                        <p class="text-center" style="margin-top: 40px">
                                            {{t($subItem->name)}}
                                        </p>
                                        @if($subItem->service_cost > 0)
                                            <p>
                                                <span>{{$subItem->service_cost}} {{t('SAR')}}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>

                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>


    </section>
@endsection