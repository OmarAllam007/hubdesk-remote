@extends('layouts.app')

@section('header')
    <h4>Dashboard</h4>
@endsection

@section('stylesheets')
    <style>
        .page-break {
            page-break-after: always
        }

        main {
            background: #f5f7fb;
        }
    </style>
@endsection

@section('body')
    <div class="w-full flex flex-col justify-center p-10" id="dashboard">
        <form action="{{route('dashboard.display',$businessUnit)}}" id="filterForm" class="">
            <a href="?pdf" class="btn btn-success btn-sm"><i class="fa fa-file"></i> {{ t('PDF') }}</a>
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

        @include('dashboard.ticket_overview')
        <div class="page-break"></div>

        <div class="">
            <div class="flex flex-wrap">
                <div class="md:w-full lg:w-full xl:w-1/2 sm:w-full  pr-3 h-100">
                    @include('dashboard.charts._yearly_performance')
                </div>
                <div class="md:w-full lg:w-full xl:w-1/2 sm:w-full h-100 pr-3">
                    @include('dashboard.charts._customer_satisfaction_over_year')
                </div>

            </div>


            <hr>
            <div class="flex flex-wrap">
                <div class="md:w-full lg:w-full xl:w-1/2 sm:w-full pr-3">
                    @include('dashboard.charts._top_services')
                </div>

                <div class="md:w-full lg:w-full xl:w-1/2 sm:w-full">
                    @include('dashboard.charts._status')
                </div>
            </div>
            <div class="flex justify-center">
                <div class="md:w-full lg:w-full xl:w-2/3 sm:w-full">
                    @include('dashboard.charts._service_performance')
                </div>
            </div>
        </div>
    </div>


    {{--        <div class="row" style="display: flex" id="topServicesChart">--}}
    {{--            <div class="col-md-6 dashboard-card-item">--}}


    {{--                <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--            <div class="col-md-6 dashboard-card-item">--}}
    {{--                <h3 style="text-align: left">Performance over the year</h3>--}}
    {{--                --}}{{--                <small>{{request('filters.from') .' - '.request('filters.to') }}</small>--}}
    {{--                <br>--}}
    {{--                <canvas id="yearlyPerformance"></canvas>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <br>--}}
    {{--        <div style="page-break-after: always "></div>--}}
    {{--        <hr>--}}

    {{--        <div class="row" style="display: flex;justify-content: space-between;" id="customerSatisfactionOverYearDiv">--}}
    {{--            --}}{{--            <div class="col-md-12 ">--}}
    {{--            <div class="col-md-8 dashboard-card-item">--}}
    {{--                <h3 style="text-align: left">Customer satisfaction over year</h3>--}}
    {{--                <br>--}}
    {{--                @if(!empty($data->customerSatisfactionOverYear))--}}
    {{--                    <canvas id="customerSatisfactionOverYear"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--            --}}{{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="row" style="display: flex; justify-content: center" id="basedOnService">--}}
    {{--            <div class="col-md-8 dashboard-card-item">--}}
    {{--                <h3 style="text-align: left">Based on Services</h3>--}}
    {{--                <br>--}}

    {{--                @if(!empty($data->ticketsByCategory))--}}
    {{--                    <canvas id="categoryChart"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <br>--}}
    {{--        <div style="page-break-after: always "></div>--}}
    {{--        <hr>--}}

    {{--        <div class="row" style="display: flex;justify-content: space-between;" id="servicePerformance">--}}
    {{--            --}}{{--            <div class="col-md-12 ">--}}
    {{--            <div class="col-md-8 dashboard-card-item">--}}
    {{--                <h3 style="text-align: left">Service Performance </h3>--}}
    {{--                <br>--}}

    {{--                @if(!empty($data->servicePerformance))--}}
    {{--                    <canvas id="subCategoryPerformanceChart"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--            --}}{{--            </div>--}}
    {{--        </div>--}}


    {{--        <div class="row">--}}
    {{--            <div class="col-md-5 dashboard-card-item" id="basedOnStatus">--}}
    {{--                <h3 style="text-align: left">Statistics based on the Status</h3>--}}
    {{--                <br>--}}
    {{--                @if(!empty($data->ticketsByStatus))--}}
    {{--                    <canvas id="byStatus"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <hr>--}}
    {{--        <div style="page-break-after: always "></div>--}}

    {{--        <div class="col-md-row">--}}
    {{--            <div class="col-md-6">--}}
    {{--                <h3 style="text-align: left">Based On the Subservice</h3>--}}
    {{--                <br>--}}

    {{--                @if(!empty($data->ticketsBySubcategory))--}}
    {{--                    <canvas id="subserviceChart"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <hr>--}}
    {{--        <div style="page-break-after: always "></div>--}}

    {{--        <div class="row" style="display: flex;justify-content: space-between;" id="servicePerformance">--}}
    {{--            --}}{{--            <div class="col-md-12 ">--}}
    {{--            <div class="col-md-12 dashboard-card-item">--}}
    {{--                <h3 style="text-align: left">Service Performance </h3>--}}
    {{--                <br>--}}

    {{--                @if(!empty($data->servicePerformance))--}}
    {{--                    <canvas id="subCategoryPerformanceChart" width="300"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data Found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--            --}}{{--            </div>--}}
    {{--        </div>--}}
    {{--        <hr>--}}
    {{--        <div style="page-break-after: always "></div>--}}

    {{--        <div class="row" style="display: flex;justify-content: space-between;">--}}
    {{--            <div class="col-md-10 dashboard-card-item">--}}

    {{--                <h3 style="text-align: left">Coordinators Performance</h3>--}}
    {{--                <br>--}}
    {{--                @if(!empty($data->ticketsByCoordinator))--}}
    {{--                    <canvas id="coordinators" width="300" height="150"></canvas>--}}
    {{--                @else--}}
    {{--                    <p>No Data found.</p>--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <br><br>--}}
    {{--        <hr>--}}
    {{--        <div style="page-break-after: always "></div>--}}

    {{--        <div class="row">--}}
    {{--            <div class="col-md-12">--}}
    {{--                <h3 style="text-align: left">Customer Satisfaction--}}
    {{--                    - {{$data->customerSatisfaction['total_responses_percentage'] }}%</h3>--}}
    {{--                --}}{{--                {{$data->customerSatisfaction['total_submitted']}} response--}}
    {{--                --}}{{--                ---}}
    {{--            </div>--}}
    {{--            <br>--}}
    {{--            <div class="col-md-10" id="customerCanvases" style="display: flex;justify-content: space-between;">--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        </div>--}}
@endsection

@section('javascript')
    <script src="/js/dashboard/index.js"></script>


    {{--    @include('dashboard.charts._yearly_performance')--}}
    {{--    @include('dashboard.charts._customer_satisfaction_over_year')--}}
    {{--    @include('dashboard.charts._category')--}}
    {{--    @include('dashboard.charts._subservice')--}}
    {{--    @include('dashboard.charts._service_performance')--}}
    {{--    @include('dashboard.charts._status')--}}
    {{--    @include('dashboard.charts._coordinators')--}}
    {{--    @include('dashboard.charts._survey')--}}

@endsection