<div class="dcard  px-3">
    <template>
        <div>
            @if(!empty($data->yearlyPerformance))
                <yearly-performance-chart
                        :labels="{{json_encode(array_keys($data->yearlyPerformance))}}"
                        :total="{{json_encode(array_values(collect($data->yearlyPerformance)->pluck('total')->toArray()))}}"
                        :resolved="{{json_encode(array_values(collect($data->yearlyPerformance)->pluck('ontime')->toArray()))}}"
                >
                </yearly-performance-chart>
            @else
            @endif
        </div>
    </template>
    <div class="pt-5"></div>
</div>