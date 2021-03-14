@extends('layouts.app')

@section('header')
    <h2>{{ $report->title }}</h2>
    <a href=""></a>
    <div class="btn-toolbar">
        <a id="exportToExcel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> {{ t('Excel') }}</a>
        <a href="/reports" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i> {{ t('Back') }}</a>
    </div>
@endsection

@section('body')

    @if($report->parameters)
        <div>
            <form action="" method="get">
                @foreach($report->parameters as $key=>$param)
                    @include('reports.inputs.'.$param['type'])
                @endforeach
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-success">{{t('Generate')}}</button>
                </div>
            </form>
        </div>
    @endif

    @if(collect($columns)->count())
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
                    @foreach($data as $item)
                        <tr>
                            @foreach($columns as $key=>$column)
                                <td>{{str_replace('&nbsp;','',strip_tags(collect($item)->toArray()[$column])) ?? '' }}</td>
                                {{--                                <td>{!! html_entity_decode(collect($item)->toArray()[$column] )?? ''   !!}</td>--}}
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>


            <div class="text-center">
                {{ $data->appends($_GET)->links() }}
            </div>

        </div>
    @endif
@endsection

@section('javascript')
    <script>
        $('#exportToExcel').on('click', () => {
            var url = new URL(window.location.href);
            url.searchParams.set('excel', '');
            window.location.href = url;
        })
    </script>
@endsection