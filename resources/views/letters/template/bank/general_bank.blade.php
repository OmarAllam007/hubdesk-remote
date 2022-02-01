<!doctype html>
<html lang="en">
@include('letters.layout._header')
<body>
<div class="flex">
    <div class="">
        <page size="A4" class="">
            <p class="pt-10"></p>
            <p class="text-gray-900 text-xl pt-64  px-20   ">HD: {{$letterTicket->ticket->id}}
                {{--                Ticket No--}}
            </p>
            <p class="text-gray-900 text-xl pt-5 px-20   ">ID: {{$letterTicket->user->employee_id ?? $letterTicket->ticket->requester->employee_id}}
                {{--            employee id--}}
            </p>
            <div class="flex justify-between">
                <p class="text-gray-900 text-xl  pt-5  px-20   ">{{$letterTicket->ticket->requester->business_unit->name}}
                    {{--                 company assigned --}}
                </p>
                <p class="text-gray-900 text-xl  pt-5   px-20   "> الموافق : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>

            <div class="flex justify-center pt-10 ">
                <p class="underline px-20   lt_text font-bold " style="font-family: Arial, Helvetica, sans-serif;">
                    خطاب : تعريف بالراتب
                </p>
            </div>

            <div class="flex  pt-20 px-20 " dir="rtl">
                <p class="lt_text">
                    {{--                    change as per Name --}}
                    الســادة / {{$letterTicket->letter->ar_name}}
                </p>
                <p class="px-64 "></p>
                {{--                <p class="px-20    "></p>--}}
                <p class="lt_text">المحترمين</p>
            </div>

            <div class="flex justify-end pt-10 px-20  ">

                <p class="lt_text" dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،، وبعد ،،،
                </p>
            </div>
            <div class="flex flex-col pt-10 px-20  ">
                <p dir="rtl" class="lt_text">
                    {{--                                        change as per SAP --}}
                    <span>
{{--                        @dd($user)--}}
                    نفيدكم نحن {{$user['sponsor_company']}} بأن الموظف / {{$user['ar_name']}}
                    </span>

                    <span>
                    ، الجنسية / {{$user['ar_nationality']}}،
                    </span>
                    <span>
                    هوية رقم/
                    {{$user['iqama_number']}}،
                    </span>
                    {{--                </p>--}}
                    {{--                <p dir="rtl" class=" text-3xl ">--}}
                    <span>
                    يعمل لدينا من تاريخ: {{$user['date_of_join']}} م بوظيفة: {{$user['occupation']}}
                    </span>

                    <span>
                    ، ويتقاضى الراتـب اســاسي ({{$user['allowances']['basic_salary']}} ريال)،
                    </span>

                    <span>
                    بدل سكن ({{$user['allowances']['housing_allowance']}} ريال ) ،
                    </span>
                    @if(isset($user['allowances']['transportation_allowance']))
                        <span>
                    بدل نقل ({{$user['allowances']['transportation_allowance']}} ريال) ،
                    </span>
                    @endif

                    @if(isset($user['allowances']['food_allowance']))
                        <span>
                    بدل طعام({{$user['allowances']['food_allowance']}}ريال) ،
                    </span>
                    @endif

                    @if(isset($user['allowances']['fixed_overtime']))
                        <span>
                    بدل إضافي ثابت ({{$user['allowances']['fixed_overtime']}}ريال)
                    </span>
                    @endif
                    @if(isset($user['allowances']['nature_of_work_allowance']))
                        <span>
                            بدل طبيعة عمل ({{$user['allowances']['nature_of_work_allowance']}} ريال) ،
                    </span>
                    @endif
                    @if(isset($user['allowances']['fixed_bonus']))
                        ، بدل  ثابت  ( {{$user['allowances']['fixed_bonus']}} ريال) ،
                    @endif
                    <span>
                        راتــب إجمـالي ({{$user['total_package']}} ريال) ،
                    </span>
                    <span>
                    وقـد أعطــي هـــذا الخطـاب
                    بنــاءً علــى طلبـه دون أدنى مسؤوليـة على الشــركة.
                    </span>
                </p>
            </div>

            <div class="flex justify-center pt-20 ">
                <p class="lt_text font-bold" dir="rtl">
                    ولكم وافر الشكر والتقدير ،،،
                </p>
            </div>


            @include('letters._footer')
            @include('letters.template._footer_query')
        </page>
    </div>


</div>
</body>
</html>