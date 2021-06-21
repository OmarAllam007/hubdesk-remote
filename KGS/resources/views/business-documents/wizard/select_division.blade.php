@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{t('Divisions')}}</h4>

    @if(auth()->user()->groups()->whereType(App\Group::KGS_ADMIN)->exists() || auth()->user()->isAdmin())
        <div class="btn-group">
            <a class="btn btn-outlined  btn-primary" href="{{route('kgs.admin.index')}}"><i
                        class="fa fa-cogs"></i> {{t('Admin Panel')}}</a>
        </div>
    @endif


@endsection

@section('body')
    <div class="flex">
        <div class="flex">
            <a href="{{route('ticket.create.select_category',6)}}"
               class=" text-center pt-2 pb-2 pl-5 pr-5  m-5 bg-gray-400 shadow-md  rounded-2xl text-viola "><i
                        class="fa fa-arrow-left"></i>
                {{t('Select Services')}}
            </a>
        </div>
    </div>

    <section class="col-md-12 card-section">
        <div class="tiles-container">
            @foreach(\App\Division::all() as $division)
                {{--                @can('show_business_unit',$division->business_unit)--}}
                <a href="{{route('kgs.business_document.select_business_unit',compact('division'))}}"
                   class="tile">
                    <div class="tile-container">
                        <div class="tile-body"
                             style="width: 100%;height: 100%;display: flex; flex-direction:column; justify-content: center;">
                            <p class="text-center">
                                {{t($division->name)}}
                            </p>
                        </div>
                    </div>
                </a>
                {{--                @endcan--}}
            @endforeach
        </div>

    </section>
@endsection