@extends('layouts.app')

@section('header')
    <h4>Select BusinessUnit</h4>
@endsection

@section('stylesheets')
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
        <div class="row">
            <div class="col-md-5">
                <h3 style="text-align: left">Based On the Service Type</h3>
                <br>

                @if(!empty($data->ticketsByCategory))
                    <canvas id="myChart" width="300" height="300"></canvas>
                @else
                    <p>No Data Found.</p>
                @endif
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
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
        <div class="row">
            <div class="col-md-12">
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
            <div class="col-md-11" id="customerCanvases" style="display: flex">
                <h3 style="text-align: left">Customer Satisfaction</h3>
                <br>
                <br>

            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>

        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'pie',
            plugins: [ChartLabels],
            data: {
                labels: {!! json_encode(array_keys($data->ticketsByCategory)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($data->ticketsByCategory)) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    labels: [
                        {
                            render: 'label',
                            position: 'outside',
                            fontSize: 10,
                            textMargin: 5,
                        },
                        {
                            render: 'percentage',
                            precision: 2,

                        },
                    ],
                },

            }
        });


        var statusData = {!! json_encode($data->ticketsByStatus) !!};
        var statusDataSet = [];
        var statusLabels = [];

        let colors = ['#FF6384', '#36A2EB'];

        let index = 0;
        for (let [key, value] of Object.entries(statusData)) {
            statusLabels.push(value['labels'][0]);
            statusDataSet.push(
                {
                    label: key,
                    data: Object.values(value['values'][0]),
                    backgroundColor: Array(value['labels'][0].length).fill(colors[index])
                }
            );

            index++;
        }
        console.log(statusDataSet)
        new Chart(document.getElementById('byStatus'), {
            type: 'bar',
            data: {
                labels: statusLabels[0],
                datasets: statusDataSet,
            },
            options: {
                plugins: {
                    labels: {
                        render: statusDataSet.length > 1 ? 'percentage' : 'value'
                    }
                }
            }
        });


        var labels = {!! json_encode(array_keys($data->ticketsByCoordinator)) !!};

        new Chart(document.getElementById('coordinators'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($data->ticketsByCoordinator)) !!},
                datasets: [
                    {
                        label: "Total",
                        data: {!! json_encode(array_values(collect($data->ticketsByCoordinator)->pluck('total')->toArray())) !!},
                        backgroundColor: Array(labels.length).fill('#FF6384')
                    },
                    {
                        label: "Resolved",
                        data: {!! json_encode(array_values(collect($data->ticketsByCoordinator)->pluck('resolved')->toArray())) !!},
                        backgroundColor: Array(labels.length).fill('#36A2EB')
                    },
                    {
                        label: "Ontime",
                        data: {!! json_encode(array_values(collect($data->ticketsByCoordinator)->pluck('resolvedOnTime')->toArray())) !!},
                        backgroundColor: Array(labels.length).fill('#27BUED')

                    }
                ]
            },
            options: {
                plugins: {
                    labels: {
                        render: 'percentage'
                    }
                }
            }
        });

        var items = {!! json_encode($data->customerSatisfaction->toArray()) !!};

        for (let [key, value] of Object.entries(items)) {
            let cKey = 'customers' + key.split(' ').join('');

            var canvasDiv = document.createElement('div');
            canvasDiv.setAttribute("style", "width:400px;margin:20px");
            var canvasElem = document.createElement('canvas');
            canvasElem.setAttribute("id", cKey);
            var question = document.createElement('p');
            question.innerText = key;

            canvasDiv.appendChild(document.createElement('br'));
            canvasDiv.appendChild(question);
            canvasDiv.appendChild(canvasElem);

            document.getElementById('customerCanvases').appendChild(canvasDiv);
            new Chart(document.getElementById(cKey), {
                type: 'pie',
                plugins: [ChartLabels],
                data: {
                    labels: Object.keys(value),
                    datasets: [{
                        data: Object.values(value),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                        labels: [
                            {
                                render: 'label',
                                position: 'outside',
                                fontSize: 10,
                                textMargin: 5,
                            },
                            {
                                render: 'percentage',
                                precision: 2,

                            },
                        ],
                    },

                }
            });
        }
    </script>
@endsection