@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Business Units')}}</h4>

    {{--    --}}
    @if(auth()->user()->groups()->whereType(App\Group::KGS_ADMIN)->exists() || auth()->user()->isAdmin())
        <div class="btn-group">
            <a target="_blank" class="btn btn-outlined  btn-primary" href="{{route('kgs.admin.index')}}"><i
                        class="fa fa-cogs"></i> {{t('Admin Panel')}}</a>
        </div>
    @endif


@endsection
@section('stylesheets')

@endsection

@section('body')
    <div class="flex">
        <div class="flex">
            <a href="{{route('kgs.business_document.select_division')}}"
               class=" text-center pt-2 pb-2 pl-5 pr-5  m-5 bg-gray-400 shadow-md  rounded-2xl text-viola "><i
                        class="fa fa-arrow-left"></i>
                {{t('Select Division')}}
            </a>
        </div>
    </div>
    <section class="col-md-12 card-section">
        <div class="tiles-container">
            @foreach($division->kgs_business_units as $business_unit)
                @can('show_business_unit',$business_unit)
                    <a href="{{route('kgs.document.select_category', ['business_unit'=>$business_unit])}}"
                       class="tile">
                        <div class="tile-container">
                            <div class="tile-body"
                                 style="width: 100%;height: 100%;display: flex; flex-direction:column; justify-content: center;">
                                <p class="text-center">
                                    {{t($business_unit->name)}}
                                </p>
                            </div>
                        </div>
                    </a>
                @endcan
            @endforeach
        </div>

    </section>
@endsection