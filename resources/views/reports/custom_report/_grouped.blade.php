@foreach($data as $groupedBy=>$row)
    <div class="alert alert-info" role="alert">
        <a href="#{{snake_case(str_replace(['.','&'],'',$groupedBy))}}" data-toggle="collapse"
           class="alert-link">   {{$groupedBy}}
            ( {{count($row)}} ) </a>
    </div>

    <div id="{{snake_case(str_replace(['.','&'],'',$groupedBy))}}" class="collapse">
        <table class="table table-bordered table-hover">
            <thead>
            <tr class="bg-primary">
                @foreach($report->parameters['fields'] as $field)
                    <td class="text-center">
                        {{str_replace('_',' ',title_case($field))}}
                    </td>
                @endforeach
            </tr>
            </thead>
            @foreach($row as $cell)
                <tr>
                    @foreach($report->parameters['fields'] as $item)
                        <td>{{$cell->$item ?? $cell->{str_replace('_',' ',title_case($item))} ?? ''}}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>

@endforeach



{{--<table class="table table-bordered table-hover">--}}
{{--    <thead>--}}
{{--    <tr class="bg-primary">--}}
{{--        @foreach($report->parameters['fields'] as $field)--}}
{{--            <td class="text-center">--}}
{{--                {{str_replace('_',' ',title_case($field))}}--}}
{{--            </td>--}}
{{--        @endforeach--}}
{{--    </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--    @foreach($data as $groupedBy=>$row)--}}
{{--        <tr class="bg-info">--}}
{{--            <td colspan="{{count($report->parameters['fields'])}}">--}}
{{--            <a   data-target="#{{snake_case($groupedBy)}}" data-toggle="collapse">    {{$groupedBy}} ( {{count($row)}} ) </a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <div id="{{snake_case($groupedBy)}}" class="collapse">--}}
{{--            @foreach($row as $cell)--}}
{{--                --}}{{--                        {{dd($cell,$report->parameters['fields'])}}--}}
{{--                <tr>--}}
{{--                    @foreach($report->parameters['fields'] as $item)--}}
{{--                        <td>{{$cell->$item ?? $cell->{str_replace('_',' ',title_case($item))} ?? ''}}</td>--}}
{{--                    @endforeach--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--        </div>--}}

{{--    @endforeach--}}
{{--    </tbody>--}}
{{--</table>--}}