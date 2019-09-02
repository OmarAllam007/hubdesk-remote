@extends('layouts.app')


@section('header')
    <h2>{{t('Report')}}</h2>
@endsection

@section('body')
    @include('reports._sidebar')
    @if($reports->count())
        <div class="col-sm-9">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{t('Report')}}</th>
                    <th>{{t('Created By')}}</th>
                    <th>{{t('Created at')}}</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>
                            @if($report->type && $report->type == \App\Report::$CUSTOM_REPORT)
                                <a href="{{route('reports.custom_report.show', $report)}}">
                                    {{$report->title}}
                                </a>
                            @else
                                <a href="{{route('reports.show', $report)}}">
                                    {{$report->title}}
                                </a>
                            @endif
                        </td>
                        <td>{{$report->user->name ?? ''}}</td>
                        <td>{{$report->created_at->format('d/m/Y H:i')}}</td>
                        <td class="col-md-2 col-sm-3">
                            <article class="actions">
                                @if($report->type && $report->type == \App\Report::$CUSTOM_REPORT)
                                    <a href="{{route('reports.custom_report.show', $report)}}"><i
                                                class="fa fa-eye"></i> {{t('View')}}
                                        @else
                                    </a><a href="{{route('reports.show', $report)}}"><i
                                                class="fa fa-eye"></i> {{t('View')}}
                                    </a>
                                @endif
                                |


                                @if($report->type && $report->type == \App\Report::$CUSTOM_REPORT)
                                    <a href="{{route('reports.custom_report.edit', $report)}}"><i
                                                class="fa fa-edit"></i> {{t('Edit')}}</a>
                                @else

                                    @if($report->core_report_id == \App\Report::$QUERY_REPORT)
                                        <a href="{{route('reports.query.edit', $report)}}"><i
                                                    class="fa fa-edit"></i> {{t('Edit')}}</a>
                                    @else
                                        <a href="{{route('reports.edit', $report)}}"><i
                                                    class="fa fa-edit"></i> {{t('Edit')}}</a>
                                    @endif

                                @endif
                            </article>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="col-md-9">
            <div class="alert alert-info">
                <p class="text-center">{{t('No reports found')}}</p>
            </div>
        </div>
    @endif
@endsection

@section('stylesheets')
    <style>
        .actions {
            display: none;
        }

        tr:hover .actions {
            display: block;
        }
    </style>
@endsection
