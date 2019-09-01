<table class="table table-bordered">
    <thead>
    <tr class="bg-primary">
        @foreach($report->parameters['fields'] as $field)
            <td class="text-center">
                {{str_replace('_',' ',title_case($field))}}
            </td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=>$row)
        <tr>
            @foreach($report->parameters['fields'] as $item)
                <td>{{$row->$item ?? $row->{str_replace('_',' ',title_case($item))} ?? ''}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center">
    {!! $data->links() !!}
</div>