@extends('layouts.app')

@section('header')
    <h2>{{ $report->title }}</h2>
    <a href=""></a>
    <div class="btn-toolbar">
        <a href="?excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> {{ t('Excel') }}</a>
        <a href="/reports" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i> {{ t('Back') }}</a>
    </div>
@endsection

@section('body')
    <div>
        <form action="" method="get">
            @foreach($report->parameters as $key=>$param)
                @include('reports.inputs.'.$param['type'])
            @endforeach
            <div class="form-group col-md-6">
                <button type="submit" class="btn btn-success">{{t('Generate')}}</button>
            </div>
        </form>
    </div>
    <div class="container-fluid report-container">
        <section class="">
            <table class="table table-condensed report-head">
                <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ t($column) }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        @foreach($item as $cell)
                            <td>{!! $cell !!}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>


        <div class="text-center">
            {{ $items->appends($_GET)->links() }}
        </div>

    </div>




@endsection