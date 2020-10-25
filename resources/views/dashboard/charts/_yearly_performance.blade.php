<div class="dcard  px-3">
    <div class="p-5">
        <h3 class="text-3xl font-bold">{{t('Performance last 9 months')}}</h3>
    </div>
    <hr>
    <div>
        @if(!empty($data->yearlyPerformance))
            <yearly-performance-chart
                    class="w-full"
                    :labels="{{json_encode(array_keys($data->yearlyPerformance))}}"
                    :total="{{json_encode(array_values(collect($data->yearlyPerformance)->pluck('total')->toArray()))}}"
                    :resolved="{{json_encode(array_values(collect($data->yearlyPerformance)->pluck('ontime')->toArray()))}}"
            >
            </yearly-performance-chart>
        @else
        @endif
    </div>

</div>