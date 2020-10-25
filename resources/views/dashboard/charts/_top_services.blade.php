<div class="dcard ">
    <div class="p-5">
        <h3 class="text-3xl font-bold">{{t('Top Services')}}</h3>
        @if(session('filters.from'))
            <small>From {{session('filters.from') }} - To: {{session('filters.to')}}</small>
        @else
            <small>{{\Carbon\Carbon::now()->submonth()->monthName}}</small>
        @endif
    </div>
    <hr>

    <div>
        @if(!empty($data->topServices))
            <service-type-chart
                    :labels="{{json_encode(array_keys($data->topServices))}}"
                    :values="{{json_encode(array_values($data->topServices))}}">
            </service-type-chart>
        @else
        @endif
    </div>
</div>