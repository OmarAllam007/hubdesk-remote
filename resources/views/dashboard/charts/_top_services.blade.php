<div class="dcard ">
    @if(request('filters.from'))
        <small>{{request('filters.from') .' - '.request('filters.to') }}</small>
    @endif
    <br>
    <template>
        <div >
            @if(!empty($data->topServices))
                <service-type-chart
                        :labels="{{json_encode(array_keys($data->topServices))}}"
                        :values="{{json_encode(array_values($data->topServices))}}">
                </service-type-chart>
            @else
            @endif
        </div>
    </template>
</div>