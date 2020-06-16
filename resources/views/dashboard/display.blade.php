@extends('layouts.app')

@section('header')
    <h4>Select BusinessUnit</h4>
@endsection

@section('stylesheets')
    <style>
        .dashboard-card-item{
            margin: 10px;
            box-shadow: 0 0 3px 0px;
            border-radius: 20px;
        }
    </style>
@endsection

@section('body')
    <div class="col-md-12 ">
        <form action="{{route('dashboard.display',$businessUnit)}}">
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

        <div class="row">
            <div class="col-md-12">
                <div class="dashboard-card">
                    <h2>Tickets Overview</h2>
                    <hr>
                    @foreach($data->ticketOverView as $key=>$tickets)
                        <h3><u>{{$key}}</u></h3>

                        <div class="tickets-overview">
                            <div>
                                <div class="ticket-shape" style="background-color:  #0079b4; color: white">
                                    {{$tickets['all']}}
                                </div>
                                <p>All Tickets</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  #4c6460; color: white">
                                    {{$tickets['open']}}
                                </div>
                                <p>Open</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  #ef996d; color: white">
                                    {{$tickets['onHold']}}
                                </div>
                                <p>OnHold</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  darkgreen; color: white">
                                    {{$tickets['resolved']}}
                                </div>
                                <p>Resolved</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  darkred; color: white">
                                    {{$tickets['overdue']}}
                                </div>
                                <p>Overdue</p>
                            </div>

                            <div>
                                <div class="ticket-shape" style="background-color:  #003900; color: white">
                                    {{$tickets['closedOnTime']}}
                                </div>
                                <p>Closed On Time</p>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
        <br>
        <hr>
        <div class="row " style="display: flex">
            <div class="col-md-5 dashboard-card-item">
                <h3 style="text-align: left">Based On the Service Type</h3>
                <br>

                @if(!empty($data->ticketsByCategory))
                    <canvas id="categoryChart"  width="300" height="300"></canvas>
                @else
                    <p>No Data Found.</p>
                @endif
            </div>
            <div class="col-md-2"></div>

            <div class="col-md-5 dashboard-card-item">
                <h3 style="text-align: left">Statistics based on the Status</h3>
                <br>
                @if(!empty($data->ticketsByStatus))
                    <canvas id="byStatus" width="300" height="300"></canvas>
                @else
                    <p>No Data Found.</p>
                @endif
            </div>
        </div>
        <hr>
        <div class="row" style="display: flex;justify-content: space-between;">
{{--            <div class="col-md-12 ">--}}
                <div class="col-md-5 dashboard-card-item">
                    <h3 style="text-align: left">Based On the Subcategory</h3>
                    <br>

                    @if(!empty($data->ticketsBySubcategory))
                        <canvas id="subCategoryChart" width="300" height="300"></canvas>
                    @else
                        <p>No Data Found.</p>
                    @endif
                </div>
{{--            </div>--}}
        </div>
        <hr>
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
        <div class="row">
            <div class="col-md-12">
            <h3 style="text-align: left">Customer Satisfaction</h3>

            </div>
            <br>
            <div class="col-md-10" id="customerCanvases" style="display: flex;justify-content: center;">


            </div>
        </div>
    </div>
@endsection

@section('javascript')

        @include('dashboard.charts._category')
        @include('dashboard.charts._subcategory')
        @include('dashboard.charts._status')
        @include('dashboard.charts._coordinators')
        @include('dashboard.charts._survey')
    <script>


    </script>
@endsection