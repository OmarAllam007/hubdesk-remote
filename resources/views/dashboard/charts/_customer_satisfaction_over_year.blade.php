<div class="dcard ">
    <div class="p-5">
        <h3 class="text-3xl font-bold">{{t('Customer satisfaction last 9 months')}}</h3>
    </div>
    <hr>
    <div >
{{--        @dd($data->customerSatisfactionOverYear)--}}
        @if(!empty($data->customerSatisfactionOverYear))
            <customer-satisfaction-chart
                    :labels="{{json_encode(array_keys($data->customerSatisfactionOverYear->toArray()))}}"
                    :values="{{json_encode(array_values($data->customerSatisfactionOverYear->toArray()))}}"
            >
            </customer-satisfaction-chart>
        @else
        @endif
    </div>
</div>
