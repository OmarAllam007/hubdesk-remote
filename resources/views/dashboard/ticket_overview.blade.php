<style>
    main {
        background: #f5f7fb;
    }
</style>
<div class="w-full">
    @foreach($data->ticketOverView as $key=>$tickets)
        <p class="text-3xl font-bold">{{$key}}</p>

        <div class="flex content-center items-center justify-between space-x-4 ">
            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl ">{{$tickets['all']}}</div>
                    <div class="text-gray-500 text-3xl mb-4">{{t('All Tickets')}}</div>
                </div>
            </div>

            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl ">{{$tickets['open']}}</div>
                    <div class="text-gray-500 text-3xl mb-4">{{t('Open')}}</div>
                </div>
            </div>

            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl ">{{$tickets['onHold']}}</div>
                    <div class="text-gray-500 text-3xl mb-4">{{t('On Hold')}}</div>
                </div>
            </div>

            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl ">{{$tickets['resolved']}}</div>
                    <div class="text-gray-500 text-3xl mb-4">{{t('Resolved')}}</div>
                </div>
            </div>

            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl text-red-500">{{$tickets['overdue']}}</div>
                    <div class="text-gray-500 text-3xl">{{t('Overdue')}}</div>
                </div>
            </div>

            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl text-red-500">{{$tickets['closedOnTime']}}</div>
                    <div class="text-gray-500 text-3xl">{{t('Closed On time')}}</div>
                </div>
            </div>

            <div class="dcard my-4 py-12 px-3 ">
                <div class="dcard-body py-3 text-center">
                    <div class="text-right text-green">

                    </div>
                    <div class="text-5xl">{{$tickets['customer_satisfaction']}} %</div>
                    <div class="text-gray-500  text-3xl">{{t('Satisfaction')}}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>


