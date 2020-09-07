@extends('layouts.app')

@section('header')
    <h4>Select BusinessUnit</h4>
@endsection

@section('stylesheets')
    <style>
        .dashboard-card-item {
            margin: 10px;
            box-shadow: 0 0 3px 0px;
            border-radius: 20px;
        }

        @media print {
            body{
                zoom: 0.5;
            }
            footer{
                display: none;
            }
            #filterForm{
                display: none;
            }
            #ticketOverView{
                zoom: 1.3;
            }
            #basedOnService{
                zoom: 1.5;
            }
            #basedOnStatus{
                zoom: 1.5;
            }
            #servicePerformance{
                zoom: 1.6;
            }

            #customerCanvases{
                flex-direction: column;
                overflow: scroll;
                overflow-x: hidden;
                zoom: 1.5;
            }
        }
    </style>
@endsection

@section('body')
    <div class="col-md-12 ">

        <form action="{{route('dashboard.display',$businessUnit)}}" id="filterForm">
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

        <div class="row" id="ticketOverView">
            <div class="col-md-12">
                <div class="dashboard-card">
                    <h2>Tickets Overview</h2>
                    <hr>
                    @foreach($data->ticketOverView as $key=>$tickets)
                        <h3><u>{{$key}}</u></h3>

                        <div class="tickets-overview">
                            <div>
                                <div class="ticket-shape" style="background-color:  #0079b4 !important; color: white !important;">
                                    {{$tickets['all']}}
                                </div>
                                <p>All Tickets</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  #4c6460 !important; color: white !important">
                                    {{$tickets['open']}}
                                </div>
                                <p>Open</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  #ef996d !important; color: white !important">
                                    {{$tickets['onHold']}}
                                </div>
                                <p>OnHold</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  darkgreen !important; color: white !important">
                                    {{$tickets['resolved']}}
                                </div>
                                <p>Resolved</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  darkred !important; color: white !important">
                                    {{$tickets['overdue']}}
                                </div>
                                <p>Overdue</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  #003900 !important; color: white!important">
                                    {{$tickets['closedOnTime']}}
                                </div>
                                <p>Closed On Time</p>
                            </div>

                            <div>
                                <div class="ticket-shape"
                                     style="background-color:  @if($tickets['customer_satisfaction'] > 60 ) #246D24 !important; @else #7C1500 !important; @endif color: white !important">
                                    {{$tickets['customer_satisfaction']}} %
                                </div>
                                <p style="line-height: 2"> Customer Satisfaction</p>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
        <br>
        <hr>
        <div style="page-break-after: always "></div>
        <div class="row " style="display: flex" id="basedOnService">
            <div class="col-md-5 dashboard-card-item">
                <h3 style="text-align: left">Based On the Service Type</h3>
                <br>

                @if(!empty($data->ticketsByCategory))
                    <canvas id="categoryChart" width="300" height="300"></canvas>
                @else
                    <p>No Data Found.</p>
                @endif
            </div>
            <div class="col-md-2"></div>
        </div>
        <div style="page-break-after: always "></div>

        <div class="col-md-5 dashboard-card-item" id="basedOnStatus">
            <h3 style="text-align: left">Statistics based on the Status</h3>
            <br>
            @if(!empty($data->ticketsByStatus))
                <canvas id="byStatus" width="300" height="300"></canvas>
            @else
                <p>No Data Found.</p>
            @endif
        </div>

        <hr>
        <div style="page-break-after: always "></div>

        <div class="col-md-12">
            <h3 style="text-align: left">Based On the Subservice</h3>
            <br>

            @if(!empty($data->ticketsBySubcategory))
                <canvas id="subserviceChart" width="200" ></canvas>
            @else
                <p>No Data Found.</p>
            @endif
        </div>
        <hr>
        <div style="page-break-after: always "></div>

        <div class="row" style="display: flex;justify-content: space-between;" id="servicePerformance">
            {{--            <div class="col-md-12 ">--}}
            <div class="col-md-12 dashboard-card-item">
                <h3 style="text-align: left">Service Performance </h3>
                <br>

                @if(!empty($data->servicePerformance))
                    <canvas id="subCategoryPerformanceChart" width="300"></canvas>
                @else
                    <p>No Data Found.</p>
                @endif
            </div>
            {{--            </div>--}}
        </div>
        <hr>
        <div style="page-break-after: always "></div>

        <div class="row" style="display: flex;justify-content: space-between;">
            <div class="col-md-10 dashboard-card-item">

                <h3 style="text-align: left">Coordinators Performance</h3>
                <br>
                @if(!empty($data->ticketsByCoordinator))
                    <canvas id="coordinators" width="300" height="150"></canvas>
                @else
                    <p>No Data found.</p>
                @endif
            </div>
        </div>
        <br><br>
        <hr>
        <div style="page-break-after: always "></div>

        <div class="row">
            <div class="col-md-12">
                <h3 style="text-align: left">Customer Satisfaction -  {{$data->customerSatisfaction['total_responses_percentage'] }}%</h3>
{{--                {{$data->customerSatisfaction['total_submitted']}} response--}}
{{--                ---}}
            </div>
            <br>
            <div class="col-md-10" id="customerCanvases" style="display: flex;justify-content: space-between;">
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    @include('dashboard.charts._category')
    @include('dashboard.charts._subservice')
    @include('dashboard.charts._service_performance')
    @include('dashboard.charts._status')
    @include('dashboard.charts._coordinators')
    @include('dashboard.charts._survey')
    <script>


    </script>
@endsection