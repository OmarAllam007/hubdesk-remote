@extends('layouts.app')

@section('header')
    <h4 class="pull-left">{{$report->title}}</h4>
    <div class="btn-group-toggle">
        <a href="{{route('reports.index')}}" class="btn  btn-default">
            <i
                    class="fa fa-chevron-left"></i></a>
        <a href="{{route('reports.custom_report.edit',compact('report'))}}" class="btn btn-warning">
            <i class="fa fa-edit"></i>
        </a>

        <a href="?excel" class="btn btn-success">

            <i class="fa fa-file-excel-o"></i>
        </a>
    </div>
@stop


@section('body')
    <div class="col-md-12">
        @if($report->parameters['group_by'] != null)
            @include('reports.custom_report._grouped')
        @else
            @include('reports.custom_report._not_grouped')
        @endif

        <div class="row">
            @if($report->parameters['summary_by'])
                @foreach(array_keys($report->parameters['summary_by']) as $key)
                    <div class="col-md-6">
                        <canvas id="chart#{{$key}}"></canvas>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    @if($report->parameters['group_by'] && count($report->parameters['summary_by']))
        <script>
                    @foreach(array_keys($report->parameters['summary_by']) as $key)
            var {{$key}} =
            document.getElementById('chart#{{$key}}').getContext('2d');
                    @endforeach

            var labels = {!! json_encode(array_keys($graph_data)) !!};
            var data = {!! json_encode(array_values($graph_data)) !!}

            @foreach(array_keys($report->parameters['summary_by']) as $key)

            new Chart({{$key}}, {
                type: '{{$key}}',
                data: {
                    datasets: [{
                        data: data,
                        backgroundColor: ["#ef652f", "#fecc46", "#fee7b2", "#4babaf", "#aed5d7", "#676766",
                            "#ef652f", "#fecc46", "#fee7b2", "#4babaf", "#aed5d7", "#676766",
                            "#ef652f", "#fecc46", "#fee7b2", "#4babaf", "#aed5d7", "#676766",
                            "#ef652f", "#fecc46", "#fee7b2", "#4babaf", "#aed5d7", "#676766",
                            "#ef652f", "#fecc46", "#fee7b2", "#4babaf", "#aed5d7", "#676766",
                            "#ef652f", "#fecc46", "#fee7b2", "#4babaf", "#aed5d7", "#676766"]
                    }],

                    labels: labels
                },


            });
            @endforeach
        </script>
    @endif
@stop
