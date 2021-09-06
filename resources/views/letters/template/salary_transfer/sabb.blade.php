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

                <p class="text-gray-900 text-xl  pt-10"> الموافق : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>
            <div class="flex  pt-10 px-10" dir="rtl">
                <p class="text-2xl  ">
                    @php
                        $IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';

                    @endphp
                    الســادة / {{$letterTicket->letter->ar_name}}
                </p>
                <p class="px-64 "></p>
                <p class="text-2xl    ">المحترمين</p>
            </div>

            <div class="flex justify-end pt-10 px-10 ">
                <p class="text-2xl  " dir="rtl">
                    تحيه طيبه وبعد ،،،
                </p>
            </div>

            <div class="flex pt-5  px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>اسم الموظف:</span>
                        <span>{{$user['ar_name']}}</span>
                    </p>
                </div>

                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>الجنسية:</span>
                        <span>{{$user['ar_nationality']}}</span>
                    </p>
                </div>

            </div>

            <div class="flex pt-5 px-10 " dir="rtl">
                <div class="w-1/2">

                    <p class="text-2xl  " dir="rtl">
                        <span>هوية رقم:</span>
                        <span>{{$user['iqama_number']}}</span>
                    </p>
                </div>
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>الرقم الوظيفي:</span>
                        <span>{{$user['employee_id']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>المسمى الوظيفي:</span>
                        <span>{{$user['occupation']}}</span>
                    </p>
                </div>

                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>القسم:</span>
                        <span>{{$user['department']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>تاريخ بداية العمل:</span>
                        <span>{{$user['date_of_join']}}</span>
                    </p>
                </div>

                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>مستحقات نهاية الخدمة :</span>
                        <span>{{$user['eos_amount']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>وضع الوظيفه:</span>
                        <span>{{$user['job_status']}}</span>
                    </p>
                </div>

                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>عقد العمل:</span>
                        <span>{{$user['work_contract']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>الراتب الاساسي:</span>
                        <span>{{$user['allowances']['basic_salary']}}</span>
                    </p>
                </div>

                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>الحسميات:</span>
                        <span>{{$user['discounts']}}</span>
                    </p>
                </div>
            </div>

            <div class="flex  pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>بدلات:</span>
                        <span>  بدل السكن: {{$user['allowances']['housing_allowance']}}</span>
                    </p>
                </div>

                @if(isset($user['allowances']['transportation_allowance']))
                    <div class="w-1/2">
                        <p class="text-2xl  " dir="rtl">
                            <span>بدل نقل:</span>
                            <span>{{$user['allowances']['transportation_allowance']}}</span>
                        </p>
                    </div>
                @endif

            </div>

            @if(isset($user['allowances']['nature_of_work_allowance']) || isset($user['allowances']['food_allowance']))
                <div class="flex  pt-5 px-10 " dir="rtl">
                    @if(isset($user['allowances']['nature_of_work_allowance']))
                        <div class="w-1/2">
                            <p class="text-2xl  " dir="rtl">
                                <span>بدل طبيعة عمل :</span>
                                <span>  {{$user['allowances']['nature_of_work_allowance']}}</span>
                            </p>
                        </div>
                    @endif
                    @if(isset($user['allowances']['food_allowance']))

                        <div class="w-1/2">
                            <p class="text-2xl  " dir="rtl">
                                <span>بدل طعام:</span>
                                <span>{{$user['allowances']['food_allowance']}}</span>
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex  pt-5 px-10 " dir="rtl">
                <div class="w-1/2">
                    <p class="text-2xl  " dir="rtl">
                        <span>تاريخ دفع الراتب:</span>
                        <span> نهاية كل شهر ميلادي </span>
                    </p>
                </div>
            </div>

            <div class="flex flex-col pt-5  px-10 ">
                <p dir="rtl" class="text-2xl  leading-9 " style="line-height: 3rem">
                    بهذا نؤكد بأن الموظف المذكور أعلاه هو موظف لدينا وقد طلب منا تحويل راتبه الشهري إلى حسابه رقم :
                    {{$user['iban']}} والموجود في فرعكم الرئيسي وبناء عليه نتعهد تعهداً غير مشروطاً وغير قابل
                    للنقض بأن نقوم بتحويل راتبه الشهري إلى الحساب المذكور وذلك في نهاية كل شهر ميلادي ،
                    كما نتعهد بإبلاغكم خطياً وعلى الفور في حالة فصله عن العمل أو إنتهاء خدماته معنا لأي سبب كان وذلك قبل
                    مغادرته المملكه العربيه السعوديه كما نتعهد بدفع مكافأه نهاية خدمته وتعويضاته إلى الحساب المذكور
                    أعلاه .
                    يسري مفعول تعهدنا كما هو مذكور أعلاه بالكامل حتى نستلم مذكرة خطية منكم تحلنا من هذا الإلتزام .
                </p>
            </div>

            <div class="flex flex-col  pt-5 px-10 " dir="rtl">
                <p class="text-2xl">
                    اسم المفوض بالتوقيع : {{config('letters.signature_name')}}
                </p>
                <p class="flex text-2xl">
                    <span class="w-1/2">
                        المسمى الوظيفي : مدير الخدمات الحكومية
                    </span>
                    <span class="w-1/2">
                             التوقيع:
                    </span>
                </p>
                <p class="flex text-2xl">
                    <span class="w-1/2"> رقم الفاكس: {{$user['fax']}}</span>
                    <span class="w-1/2">رقم هاتف العمل: {{$user['phone']}}</span>
                </p>
            </div>

            <div class="border border-black border-1 mx-20  mt-10 "></div>

            <div class="flex flex-col pt-5  px-10 ">
                <p dir="rtl" class="text-2xl  leading-9 " style="line-height: 3rem">
                    أفوض أنا / {{$user['ar_name']}} ، هوية رقم : {{$user['iqama_number']}} ، البنك السعودي البريطاني تفويضاً غير
                    مشروطاً وغير قابل للنقض بخصم أية مبالغ مستحقه للبنك وذلك من حسابي رقم : {{$user['iban']}} ،
                    والموجود في فرعكم الرئيسي ، كما أفوض وأتنازل للبنك السعودي البريطاني تنازلاً غير مشروطاً وغير قابل
                    للنقض عن راتبي الشهري ومستحقاتي الأخرى والمستحقه لي من وظيفتي مع {{$user['sponsor_company']}} .
                </p>
            </div>

            <div class="flex flex-col  pt-5 px-10 " dir="rtl">
                <p class="text-2xl">
                     التوقيع :
                </p>
                <p class="flex text-2xl">
                    <span class="w-1/2">
                        التاريخ: {{$letterTicket->last_approval_date}}
                    </span>
                    <span class="w-1/2">
                             المكان:
                    </span>
                </p>
            </div>
        </page>

        @include('letters.template.bank.general_bank')
    </div>

</div>
</body>
</html>