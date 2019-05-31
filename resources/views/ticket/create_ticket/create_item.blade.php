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
                            href="{{route('ticket.create.select_category',compact('business_unit','category'))}}"> {{$category->name}}
                    </a>
                </li>

                <li class="breadcrumb-item"><a
                            href="{{route('ticket.create.select_subcategory',compact('business_unit','category'))}}"> {{$subcategory->name}}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    {{t('Select Item')}}
                </li>
            </ol>
        </nav>
    </div>
@endsection


@section('body')
    <section class="col-sm-12">

            <div class=form-group></div>
            <h3 class=text-center>{{t('Items') }}</h3>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="tiles-container">
                    @foreach($subcategory->items as $item)
                        @if($item->canDisplay(\App\ServiceUserGroup::$ITEM))
                        <a href="{{route('ticket.create-ticket',compact('business_unit','category','subcategory','item'))}}"
                           class="tile">
                            <div class="tile-container"
                                 style="display: flex;align-items: center;">
                                <div class="tile-body" style="display: flex;flex-direction: column">
                                    <p class="text-center" style="min-height: 100px">
                                        {{t($item->name)}}
                                    </p>

                                    <p class="text-center">
                                        ( {{$item->service_cost ?? ''}} )
                                    </p>
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