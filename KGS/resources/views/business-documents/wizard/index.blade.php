@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>

    {{--    --}}
    @if(auth()->user()->groups()->whereType(App\Group::KGS_ADMIN)->exists() || auth()->user()->isAdmin())
        <div class="btn-group">
            <a class="btn btn-outlined  btn-primary" href="{{route('kgs.admin.index')}}"><i
                        class="fa fa-cogs"></i> {{t('Admin Panel')}}</a>
        </div>
    @endif


@endsection
@section('stylesheets')
    <style>
        @keyframes slideInFromUp {
            0% {
                transform: translateX(20%);
            }
            100% {
                transform: translateX(0);
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

        .logo-animation {
            animation: 1.5s ease-out 0s 1 slideInFromRight;
        }

        .quot-animation {
            animation: 1.5s ease-out 0s 1 slideInFromLeft;
        }

        p.quot-animation {
            margin-top: 80px;
            font-size: 18pt;
            font-weight: 800;
        }
    </style>
@endsection

@section('body')
    <section class="col-md-12 card-section">
        {{--        <h3 class=text-center>{{t('Select Business Unit') }}</h3>--}}

        <div class="tiles-container">
            @foreach(\KGS\KGSBusinessUnit::businessUnits()->get() as $business_unit)
                @can('show_business_unit',$business_unit->business_unit)
                    <a href="{{route('kgs.document.select_category', ['business_unit'=>$business_unit->business_unit])}}"
                       class="tile">
                        <div class="tile-container">
                            <div class="tile-body"
                                 style="width: 100%;height: 100%;display: flex; flex-direction:column; justify-content: center;">
                                <p class="text-center">
                                    {{t($business_unit->business_unit->name)}}
                                </p>
                            </div>
                        </div>
                    </a>
                @endcan
            @endforeach
        </div>

    </section>
@endsection