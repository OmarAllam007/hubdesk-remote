<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$letterTicket->ticket->id}}</title>
    <style>
        @media print {
            body {
                display: table;
                table-layout: fixed;
                height: auto;
            }
        }

        page {
            display: block;
            margin: 0 auto;
            background-repeat: no-repeat;
            @if(\App\LetterTicket::isApprovedTicket($letterTicket->ticket))
                            background-image: url({{url("/storage/headers/{$letterTicket->header}/image.jpg")}}) !important;
            @endif
                            background-size: contain !important;
        }


        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        }

    </style>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}?version={{time()}}">

</head>
<body>
<div class="flex">
    <div class="">
        <page size="A4" class="">
            <div class="pt-32"></div>
            <div class="pt-10 "></div>
            <div class="flex justify-end pt-20 px-10 ">
                <p class="text-gray-900 text-xl  pt-48">  التاريخ : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>
            <div class="flex  pt-10 px-10" dir="rtl">
                <p class="text-3xl ">
                    @php
                        $IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';

                    @endphp
                    الســادة / {{$user['sponsor_company']}}
                </p>
                <p class="px-64 "></p>
                <p class="text-3xl ">المحترمين</p>
            </div>

            <div class="flex justify-center pt-10 px-10 ">
                <p class="text-3xl " dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،، وبعد ،،،
                </p>
            </div>

            <div class="flex flex-col pt-5  px-10 ">
                <p dir="rtl" class="text-3xl   leading-9 " style="line-height: 3rem">
                    أفيد سيادتكم برغبتي الاستفادة من خدمات ({{$letterTicket->letter->ar_name}}) ، ولهذا أرغب بأن يتم صرف
                    راتبي الشهري إلى
                    ( {{$letterTicket->letter->ar_name}} ) حساب رقم ( {{$user['iban']}} ) كما أرجو في حال انتهاء خدماتي
                    لأي
                    سبب كان بأن
                    يتم تحويل كافة مستحقاتي إلى حسابي المشار أعلاه وأن لا يتم الإجراء إلا بخطاب موجه لكم من البنك
                    المذكور .
                </p>
            </div>

            <div class="flex justify-end pt-5  px-10 ">
                <p class="text-3xl " dir="rtl">
                    الاسم:
                </p>
            </div>

            <div class="flex justify-end   px-10 ">
                <p class="text-3xl " dir="rtl">
                    التوقيع:
                </p>
            </div>
            <div class="pt-5"></div>
            <hr>
            <div class="flex  pt-10 px-10" dir="rtl">
                <p class="text-3xl  ">
                    @php
                        $IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';

                    @endphp
                    الســادة / {{$letterTicket->letter->ar_name }}
                </p>
                <p class="px-64 "></p>
                <p class="text-3xl  ">المحترمين</p>
            </div>
            <div class="flex justify-center pt-5  px-10 ">
                <p class="text-3xl  " dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،، وبعد ،،،
                </p>
            </div>
            <div class="flex flex-col pt-5  px-10 ">
                <p dir="rtl" class="text-3xl leading-9 " style="line-height: 3rem">
                    الموضوع رواتب ومستحقات موظفنا السيد : {{$user['ar_name']}} حيث أن المذكور
                    ({{$user['ar_nationality']}}) الجنسية
                    يعمل لدينا قد أخطرنا بأنه يرغب بحصوله على تمويل شخصي منكم ويطيب لنا في ذلك الصدد أن نقوم بتحويل
                    رواتبه الشهرية إليكم في تواريخ استحقاقها وأن نقوم كذلك في حال انتهاء خدماته نتعهد بإبلاغكم خطياً ومن
                    ثم تحويل جميع مستحقاته النظامية إليكم وتستمر تعهداتنا المدرجة هنا نافذة وسارية المفعول لحين انتهاء
                    خدمة المذكور لدينا واستلامنا أشعار خطي منكم بإعفائنا من التزاماتنا الواردة أعلاه علماً بأنه مازال
                    يعمل لدينا حتى تاريخه .
                </p></div>

            @include('letters._footer')
            @include('letters.template._footer_query')

        </page>


        <page size="A4" class="">
            <p class="pt-10"></p>
            <p class="text-gray-900 text-xl pt-64  px-5 ">
                {{--                HD: {{$letterTicket->ticket->id}}--}}
                {{--                Ticket No--}}
            </p>
            <p class="text-gray-900 text-xl pt-10 px-5 ">
                {{--                ID: {{$letterTicket->ticket->requester->employee_id}}--}}
                {{--            employee id--}}
            </p>
            <div class="flex justify-between">
                <p class="text-gray-900 text-xl  pt-10  px-5 ">
                    {{--                    {{$letterTicket->ticket->requester->business_unit->name}}--}}
                </p>
                <p class="text-gray-900 text-xl  pt-10  px-5 "> التاريخ : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>

            <div class="flex  pt-20 px-10" dir="rtl">
                <p class="text-3xl">
                    {{--                    change as per Name --}}
                    الســادة / {{$letterTicket->letter->ar_name}}
                </p>
                <p class="px-64 "></p>
                {{--                <p class="px-10   "></p>--}}
                <p class="text-3xl">المحترمين</p>
            </div>

            <div class="flex justify-end pt-10 px-10">

                <p class="text-3xl" dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،، وبعد ،،،
                </p>
            </div>
            <div class="flex flex-col pt-10 px-10 ">
                <p dir="rtl" class="text-3xl">
                    {{--                                        change as per SAP --}}
                    <span>
{{--                        @dd($user)--}}
                    نفيدكم بأن السيد / {{$user['ar_name']}}
                    </span>

                    <span>
                    ، الجنسية / {{$user['ar_nationality']}}،
                    </span>
                    <span>
                    هوية رقم/
                    {{$user['iqama_number']}}،
                    </span>
                </p>
                <p dir="rtl" class=" text-3xl ">
                    <span>
                    يعمل لدينا من تاريخ: {{$user['date_of_join']}} م بوظيفة: {{$user['occupation']}}
                    </span>

                    <span>
                                                ولا يزال على رأس العمل حتى تاريخه
                    ، ويتقاضى الراتـب اســاسي ({{$user['allowances']['basic_salary']}} ريال)،

                    </span>

                    @if(isset($user['allowances']['housing_allowance']))
                        <span>
                    بدل سكن ({{$user['allowances']['housing_allowance']}} ريال ) ،
                    </span>
                    @endif

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

                        بإجمالي قدره ({{$user['total_package']}} ريال) ،
                    </span>
                    <span>
                   وقد أصدر هذا الخطاب بناءً على طلب الموظف لتقديمه إلى إدارتكم دون أدنى مسئولية على الشركة أو منسوبيها.
                    </span>
                </p>
            </div>


            <div class="flex justify-center pt-20 ">
                <p class="text-3xl" dir="rtl">
                    ولكم جزيل الشكر والتقدير ؛؛؛
                </p>
            </div>


            @include('letters._footer')
            @include('letters.template._footer_query')
        </page>
    </div>

</div>
</body>
</html>