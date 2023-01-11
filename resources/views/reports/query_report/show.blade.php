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
        <div class="p-3">
            <div class="flex w-full shadow-sm">
                <section class="overflow-x-auto rounded-md p-2 ">
                    <table class="table-fixed">
                        <thead class="bg-gray-200">
                        <tr>
                            @foreach($columns as $column)
                                <th
                                        class="w-1/2 px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center  font-semibold text-gray-600 uppercase tracking-wider">{{ t($column) }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr class="bg-white hover:bg-yellow-100">
                                @foreach($columns as $key=>$column)
                                    <td class="text-gray-700 px-5 py-3 border-b-2 border-gray-200 text-center text-md text-black  tracking-wider">{{str_replace('&nbsp;','',strip_tags(collect($item)->toArray()[$column])) ?? '' }}</td>
                                    {{--                                <td>{!! html_entity_decode(collect($item)->toArray()[$column] )?? ''   !!}</td>--}}
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
        </div>


        <div class="text-center">
            {{ $data->appends($_GET)->links() }}
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