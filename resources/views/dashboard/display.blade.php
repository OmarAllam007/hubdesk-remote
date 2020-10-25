@extends(request()->has('print') ? 'layouts.print' : 'layouts.app')

@section('header')
    <h4> {{$businessUnit->name}} > Dashboard</h4>
@endsection

@section('stylesheets')
    <style>
        main {
            background: #f5f7fb;
        }
    </style>
@endsection

@section('body')
    <div class="w-full  p-10" id="dashboard">
        <form action="{{route('dashboard.display',$businessUnit)}}" id="filterForm" class="">
            <a href="?print" class="btn btn-success btn-sm"><i class="fa fa-file"></i> {{ t('PDF') }}</a>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="form-group col-md-4">
                    <label for="">From</label>
                    <input type="date" name="filters[from]" value="{{request('filters.from')}}"
                           class="form-control dashboard-input">
                </div>
                <div class="form-group col-md-4">
                    <label for="">To</label>
                    <input type="date" value="{{request('filters.to')}}" name="filters[to]"
                           class="form-control dashboard-input">
                </div>
                <div class="form-group col-md-2">
                    <label for=""> </label>
                    <p>
                        <button class="btn btn-success btn-lg">
                            <i class="fa fa-filter"></i>
                            Filter
                        </button>

                        <a href="{{route('dashboard.display',$businessUnit)}}" class="btn btn-danger btn-lg">
                            <i class="fa fa-times"></i>
                            Clear
                        </a>
                    </p>

                </div>
            </div>
        </form>


        <p class="text-3xl text-center print-only mb-4">{{$businessUnit->name}}</p>

        <div class="page-break">
            @include('dashboard.ticket_overview')
        </div>


        <div class="xl:flex">
            <div class="xl:flex-1" style="margin-right: 10px">
                @include('dashboard.charts._yearly_performance')
            </div>
            <div class="xl:flex-1">
                @include('dashboard.charts._customer_satisfaction_over_year')
            </div>
        </div>
        <div class="page-break"></div>

        <div class="xl:flex">
            <div class="xl:flex-1" style="margin-right: 10px">
                @include('dashboard.charts._status')
            </div>

            <div class="xl:flex-1">
                @include('dashboard.charts._top_services')
            </div>
        </div>

        <div>
            <div class="">
                @include('dashboard.charts._service_performance')
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/dashboard/index.js') }}"></script>


    {{--    @include('dashboard.charts._yearly_performance')--}}
    {{--    @include('dashboard.charts._customer_satisfaction_over_year')--}}
    {{--    @include('dashboard.charts._category')--}}
    {{--    @include('dashboard.charts._subservice')--}}
    {{--    @include('dashboard.charts._service_performance')--}}
    {{--    @include('dashboard.charts._status')--}}
    {{--    @include('dashboard.charts._coordinators')--}}
    {{--    @include('dashboard.charts._survey')--}}

@endsection