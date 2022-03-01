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
                <p class="text-gray-900 text-xl pt-10"> الموافق : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>
            <div class="flex justify-center pt-10">
                <p class="text-4xl  font-bold">التزام بتحويل راتب لموظف</p>
            </div>

            <div class="flex pt-10 px-10" dir="rtl">
                <p class="text-3xl font-bold ">
                    @php
                        $IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';

                    @endphp
                    المكرمون / {{$letterTicket->letter->ar_name}}
                </p>
            </div>

            <div class="flex justify-end pt-5 px-10 ">
                <p class="text-2xl font-bold " dir="rtl">
                    السلام عليكم ورحمة الله وبركاته
                </p>
            </div>

            <div class="flex pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>اسم الموظف:</span>
                        <span>{{$user['ar_name']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex pt-5  px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>رقم بطاقة العمل/الرقم الوظيفي:</span>
                        <span>{{$user['employee_id']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex pt-5 px-10" dir="rtl">
                <div class="w-1/2">

                    <p class="text-2xl  " dir="rtl">
                        <span>رقم إثبات الهوية:</span>
                        <span>{{$user['iqama_number']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex pt-5 px-10" dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>مســمى الـوظيــــفة:</span>
                        <span>{{$user['occupation']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10" dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>تاريخ الالتحاق بالعمل:</span>
                        <span>{{$user['date_of_join']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10" dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>مستحقات نهاية الخدمة (حتى تاريخه):</span>
                        <span>{{$user['eos_amount']}}  ريال</span>
                    </p>
                </div>
            </div>

            {{--            <div class="flex  pt-5 px-10 " dir="rtl">--}}
            {{--                <div class="w-1/2">--}}
            {{--                    <p class="text-2xl  " dir="rtl">--}}
            {{--                        <span>مستحقات نهاية الخدمة (حتى تاريخه):</span>--}}
            {{--                        <span>{{$user['eos_amount']}}</span>--}}
            {{--                    </p>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            {{--            <div class="flex  pt-5 px-10 " dir="rtl">--}}
            {{--                <div class="w-1/2">--}}
            {{--                    <p class="text-2xl  " dir="rtl">--}}
            {{--                        <span>مستحقات نهاية الخدمة في حالة الاستقالة قبل نهاية العقد:</span>--}}
            {{--                        <span>{{$user['eos_amount']}}</span>--}}
            {{--                    </p>--}}
            {{--                </div>--}}
            {{--            </div>--}}


            <div class="flex flex-col pt-5  px-10">
                <p dir="rtl" class="text-2xl  leading-9 " style="line-height: 3rem">
                    تقدم موظفنا الموضحة بياناته أعلاه طالباً تحويل راتبه وبدلاته الشهرية وجميع مستحقاته الوظيفية إلى
                    حسابه الجاري رقم ({{$user['iban']}}) لأجل سداد الديون التي سوف تترتب عليه لصالح مصرف الراجحي.
                    لذا نود أن نؤكد لكم موافقتنا والتزامنا بهذا التحويل في مواعيده الشهرية واستمراره حتى نهاية علاقته
                    الوظيفية معنا مع التزامنا بعدم تسليم الموظف المذكور مستحقات نهاية الخدمة أو أي مستحقات أخرى حتى نحصل
                    على مخالصة معتمدة منكم تفيد بانتهاء الالتزامات المترتبة عليه لصالحكم وموافقتكم على إلغاء تحويل
                    راتبه.
                    وتقبلوا تحياتي.
                </p>
            </div>


            @include('letters._footer')
        </page>

        @include('letters.template.bank.alrajhi_bank')
    </div>
</div>

</body>
</html>