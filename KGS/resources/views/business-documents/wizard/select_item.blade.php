@extends('layouts.app')

@section('header')
    <h4 class="pull-left"> {{t($business_unit->name)}} > {{t($category->name)}} > {{t($subcategory->name)}}</h4>
@stop
@section('stylesheets')
    <style>
        @keyframes slideInFromUp {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes slideInFromRight {
            0% {
                transform: translateX(5%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .card-section {
            animation: .5s ease-out 0s 1 slideInFromUp;
            padding: 30px;
        }

    </style>
@endsection

@section('body')
    <section class="col-md-12 card-section">
        <div class=form-group></div>
        <div class="tiles-container">
            @foreach($subcategory->items()->corporate()->get() as $item)
                <a href="{{route('kgs.document.check-requirements',compact('business_unit','category','subcategory','item'))}}"
                   class="tile">
                    <div class="tile-container">
                        <div class="tile-body" style="width: 100%;height: 100%;display: flex; flex-direction:column;">
                            <p class="text-center" style="margin-top: 40px">
                                {{t($item->name)}}
                            </p>
                            @if($item->service_cost > 0)
                                <p>
                                    <span>{{$item->service_cost}} {{t('SAR')}}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>
@endsection