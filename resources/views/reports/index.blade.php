@extends('layouts.app')


@section('header')
    <h2>{{t('Reports')}}</h2>
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
                    @can('show',$report)
                        <tr>
                        <td>
                            <a href="{{route('reports.show', $report)}}">
                                {{$report->title}}
                            </a>
                        </td>
                        <td>{{$report->user->name ?? ''}}</td>
                        <td>{{$report->created_at->format('d/m/Y H:i')}}</td>
                        <td class="col-md-2 col-sm-3">
                            <article class="actions">
                                @if($report->type && $report->type == \App\Report::$CUSTOM_REPORT)
                                    <a href="{{route('reports.custom_report.show', $report)}}"><i
                                                class="fa fa-eye"></i> {{t('View')}}
                                        @elseif($report->core_report_id == \App\Report::$QUERY_REPORT)
                                    </a><a href="{{route('reports.query_report.show', $report)}}">
                                        <i class="fa fa-eye"></i> {{t('View')}}
                                    </a>
                                @else
                                    <a href="{{route('reports.show', $report)}}">
                                        <i class="fa fa-eye"></i> {{t('View')}}
                                    </a>

                                @endif


                                    @can('edit',$report)
                                        |
                                        <a href="{{route('reports.edit', $report)}}"><i
                                                    class="fa fa-edit"></i> {{t('Edit')}}</a>
                                    @endcan
{{--                                @if($report->type && $report->type == \App\Report::$CUSTOM_REPORT)--}}
{{--                                    <a href="{{route('reports.custom_report.edit', $report)}}"><i--}}
{{--                                                class="fa fa-edit"></i> {{t('Edit')}}</a>--}}
{{--                                @elseif($report->type && $report->type == \App\Report::$QUERY_REPORT)--}}
{{--                                    <a href="{{route('reports.query.edit', $report)}}"><i--}}
{{--                                                class="fa fa-edit"></i> {{t('Edit')}}</a>--}}
{{--                                @else--}}
{{--                                    <a href="{{route('reports.edit', $report)}}"><i--}}
{{--                                                class="fa fa-edit"></i> {{t('Edit')}}</a>--}}
{{--                                @endif--}}
                            </article>
                        </td>
                    </tr>
                    @endcan
                @endforeach
                </tbody>
            </table>
            {!! $reports->appends($_GET)->links() !!}
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
