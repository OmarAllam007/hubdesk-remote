<div>
    @foreach($data->ticketOverView as $key=>$tickets)
        <p class="text-3xl font-bold">{{session('filters.from') ? session('filters.from') . ' - ' . session('filters.to') : $key}}</p>

        <div class="flex flex-wrap justify-start space-x-3">
            {{--            @include('dashboard.charts._overview_card',$tickets['all'],t('All Tickets'))--}}
            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl ">{{$tickets['all']}}</div>
                    <div class="text-gray-500 text-xl mb-4">{{t('All Tickets')}}</div>
                </div>
            </div>

            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl ">{{$tickets['open']}}</div>
                    <div class="text-gray-500 text-xl mb-4">{{t('Open')}}</div>
                </div>
            </div>

            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl ">{{$tickets['onHold']}}</div>
                    <div class="text-gray-500 text-xl mb-4">{{t('On Hold')}}</div>
                </div>
            </div>

            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl ">{{$tickets['resolved']}}</div>
                    <div class="text-gray-500 text-xl mb-4">{{t('Resolved')}}</div>
                </div>
            </div>

            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl text-red-500">{{$tickets['overdue']}}</div>
                    <div class="text-gray-500 text-xl">{{t('Overdue')}}</div>
                </div>
            </div>

            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl text-green-500">{{$tickets['closedOnTime']}}</div>
                    <div class="text-gray-500 text-xl">{{t('Closed On time')}}</div>
                </div>
            </div>

            <div class="dcard my-4 w-64 print:w-1/6">
                <div class="dcard-body p-4 text-center">
                    <div class="text-3xl">{{$tickets['customer_satisfaction']}} %</div>
                    <div class="text-gray-500  text-xl">{{t('Satisfaction')}}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>


