<div class="dcard">
    <div class="p-5 border-b">
        <h3 class="text-3xl font-bold">{{t('Service Performance')}}</h3>
        @if(session('filters.from'))
            <small>From {{session('filters.from') }} - To: {{session('filters.to')}}</small>
        @else
            <small>{{\Carbon\Carbon::now()->submonth()->monthName}}</small>
        @endif
    </div>

        <div >
            @if(!empty($data->servicePerformance))

                <service-performance-chart
                        class="w-full"
                        :labels="{{json_encode(array_keys($data->servicePerformance))}}"
                        :total="{{json_encode(array_values(collect($data->servicePerformance)->pluck('total')->toArray()))}}"
                        :resolved="{{json_encode(array_values(collect($data->servicePerformance)->pluck('resolved')->toArray()))}}"
                        :ontime="{{json_encode(array_values(collect($data->servicePerformance)->pluck('resolvedOnTime')->toArray()))}}"
                >
                </service-performance-chart>
            @else
            @endif
        </div>
</div>

{{--<script>--}}

{{--    var labels = {!! json_encode(array_keys($data->servicePerformance)) !!};--}}

{{--    new Chart(document.getElementById('subCategoryPerformanceChart'), {--}}
{{--        type: 'bar',--}}
{{--        data: {--}}
{{--            labels: {!! json_encode(array_keys($data->servicePerformance)) !!},--}}
{{--            datasets: [--}}
{{--                {--}}
{{--                    label: "Total",--}}
{{--                    data: {!! json_encode(array_values(collect($data->servicePerformance)->pluck('total')->toArray())) !!},--}}
{{--                    backgroundColor: Array(labels.length).fill('rgba(173,69,9,0.96)')--}}
{{--                },--}}
{{--                {--}}
{{--                    label: "Resolved",--}}
{{--                    data: {!! json_encode(array_values(collect($data->servicePerformance)->pluck('resolved')->toArray())) !!},--}}
{{--                    backgroundColor: Array(labels.length).fill('rgba(11,76,74,0.81)')--}}
{{--                },--}}
{{--                {--}}
{{--                    label: "Ontime",--}}
{{--                    data: {!! json_encode(array_values(collect($data->servicePerformance)->pluck('resolvedOnTime')->toArray())) !!},--}}
{{--                    backgroundColor: Array(labels.length).fill('#27BUED')--}}

{{--                }--}}
{{--            ]--}}
{{--        },--}}
{{--        options: {--}}
{{--            plugins: {--}}
{{--                labels: {--}}
{{--                    render: 'value',--}}
{{--                    fontSize: 16,--}}
{{--                }--}}
{{--            },--}}
{{--            responsive: true,--}}

{{--            scales: {--}}
{{--                xAxes: [{--}}
{{--                    display: true,--}}
{{--                    fontSize:16,--}}
{{--                    barPercentage: 0.4--}}

{{--                }],--}}
{{--                yAxes: [{--}}
{{--                    display: true,--}}
{{--                    fontSize:16,--}}
{{--                    render: 'value',--}}
{{--                    ticks: {--}}
{{--                        suggestedMin: 0,--}}
{{--                    }--}}
{{--                }]--}}
{{--            },--}}
{{--            ticks:{--}}
{{--                beginAtZero:true--}}
{{--            }--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
