<div class="dcard   ">
    <template>
        <div>
            @if(!empty($data->customerSatisfactionOverYear))
                <customer-satisfaction-chart
                        :labels="{{json_encode(array_keys($data->customerSatisfactionOverYear->toArray()))}}"
                        :values="{{json_encode(array_values($data->customerSatisfactionOverYear->toArray()))}}"
                >
                </customer-satisfaction-chart>
            @else
            @endif
        </div>
    </template>
</div>
