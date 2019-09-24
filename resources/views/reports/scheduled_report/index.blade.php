@extends('layouts.app')


@section('header')
    <h2>{{t('Scheduled Reports')}}</h2>
@endsection

@section('body')
    @include('reports._sidebar')

    @if($reports->count())
        <div class="col-sm-9">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{t('ID')}}</th>
                    <th>{{t('Type')}}</th>
                    <th>{{t('Created at')}}</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>
                            <a href="{{route('reports.scheduled_report.edit', $report)}}">
                                #{{$report->id}}
                            </a>
                        </td>
                        <td>{{$report->report_type_str}}</td>
                        <td>{{$report->created_at->format('d/m/Y H:i')}}</td>
                        <td class="col-md-2 col-sm-3">
                            <article class="actions">

                                <form action="{{route('reports.scheduled_report.execute',$report)}}" method="post">
                                    {{method_field('post')}} {{csrf_field()}}
                                <a class="btn btn-warning" href="{{route('reports.scheduled_report.edit', $report)}}">
                                    <i class="fa fa-edit"></i> {{t('Edit')}}</a>

                                    <button class="btn btn-success">
                                        <i class="fa fa-refresh"></i>
                                        {{t('Run')}}</button>
                                </form>
                            </article>
                        </td>
                    </tr>
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

@endsection
