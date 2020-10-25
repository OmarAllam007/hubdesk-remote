<div class="dcard ">
    <div class="p-5">
        <h3 class="text-3xl font-bold">{{t('Statistics Based on Status')}}</h3>
        <small>{{\Carbon\Carbon::now()->submonth()->monthName}}</small>
    </div>
    <hr>
    <div>
        @if(!empty($data->customerSatisfactionOverYear))
            <status-chart
                    :labels="{{json_encode($data->ticketsByStatus->pluck('name')->toArray())}}"
                    :values="{{json_encode($data->ticketsByStatus->pluck('count')->toArray())}}"
            >
            </status-chart>
        @else
        @endif
    </div>
</div>