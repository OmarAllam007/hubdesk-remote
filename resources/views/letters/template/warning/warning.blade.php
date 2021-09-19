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
            <p class="pt-32"></p>
            <p class="pt-10"></p>
            <p class="pt-10"></p>
            <p class="text-gray-900 text-xl pt-5 px-5 ">HD: {{$letterTicket->ticket->id}}
            <p class="pt-5"></p>
            <p class="text-gray-900 text-xl px-5 ">SAP ID: {{$letterTicket->ticket->requester->employee_id}}
                {{--            employee id--}}
            </p>
            <div class="flex " dir="rtl">
                <p class="text-gray-900 text-xl  pt-10  px-5 "> الموافق : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>


            <div class="flex justify-center pt-5  px-10" dir="rtl">
                <p class="text-4xl">
                    إنذار أو عقوبة
                </p>
                {{--                <p class="text-4xl">المحترمين</p>--}}
            </div>

            <div class="flex justify-end pt-5 px-10 ">

                <p class="text-3xl" dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،، وبعد ،،،
                </p>
            </div>
            <div class="flex flex-col pt-5  px-10 ">
                <p dir="rtl" class="text-2xl ">
                    {{--                                        change as per SAP --}}
                    <span dir="rtl">
{{--                        @dd($user)--}}
                    الإسم / {{$user['ar_name']}}
                    </span>
                </p>
                <p dir="rtl" class="text-2xl ">
                    <span dir="rtl">
                     الرقم الوظيفي / {{$user['employee_id']}} ،
                    </span>
                </p>

                @php
                    $EsharNo = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','اشعار عقوبة رقم')->first()->value : '';
                    $violationTableAr = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','جدول المخالفات والجزاءات المتعلقة')->first()->value : '';
                    $pNumberAr = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','الفقرة رقم')->first()->value : '';
                    $pContent = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','نص الفقرة')->first()->value : '';
                    $violationsAr = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','المخالفات')->first()->value : '';
                    $punishmentsAr = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','المخالفات')->first()->value : '';

                    $EsharEnNo = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','Notice of punishment No')->first()->value : '';
                    $relatedToEn = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','Related table of violations and penalties')->first()->value : '';
                    $pNumberEn = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','paragraph No')->first()->value : '';
                    $violationsEn = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','violations')->first()->value : '';
                    $punishmentsEn = $letterTicket->ticket->fields->count() ? $letterTicket->ticket->fields->where('name','Punishments')->first()->value : '';

                @endphp
                <p dir="rtl" class="text-2xl ">
                    <span>
                     اشعار عقوبة رقم / {{$EsharNo}} ،
                    </span>
                </p>

                <p dir="rtl" class=" text-2xl  ">
                    <span>
                    حسب لائحة تنظيم العمل ، ووفقاً لجداول المخالفات والجزاءات المتعلقة {{$violationTableAr}} ، الفقرة رقم : {{$pNumberAr}} والتي تنص على {{$pContent}}  ولما قمتم به من مخالفات :
                    </span>
                </p>
                <p dir="rtl" class="text-2xl ">
                    {{$violationsAr}}
                </p>

                <p dir="rtl" class="text-2xl ">
                    عليه تقرر توجيه ({{$punishmentsAr}}) راجين عدم تكرار ذلك على أمل أن تكون في مستوى المسؤولية والتقيد
                    أكثر بالإجراءات والأنظمة المعمول بها في الشركة حتى لا نضطر لإتخاذ إجراءات أخرى أشد بحقك حسب النظام
                </p>
            </div>

            <div class="flex justify-between px-20 pt-10 ">
                <p dir="rtl" class="text-2xl ">
                    توقيع الموظف
                </p>
                <p dir="rtl" class="text-2xl ">
                    إدارة الموارد البشرية
                </p>
            </div>

            <div class="border  border-1 mt-20 mb-5 mx-20  "></div>


            <div class="flex justify-center">
                <p class="text-2xl">
                    Punishment
                </p>
            </div>

            <div class="flex flex-col px-10">
                <p class="text-2xl">
                    Name: {{$user['en_name']}}
                </p>

                <p class="text-2xl">
                    Emp. ID: {{$user['employee_id']}}
                </p>

                <p class="text-2xl">
                    Notice of punishment No: {{$EsharEnNo}}
                </p>

                <p class="text-2xl">
                    Notice of punishment No: {{$EsharEnNo}}
                </p>

                <p class="text-2xl">
                    According to the regulation of work organization, and according to the related table of violations
                    and
                    penalties, related to {{$relatedToEn}}, paragraph No. {{$pNumberEn}}, and for the violation have
                    been done by:
                    {{$violationsEn}}.
                </p>

                <p class="text-2xl">
                    Based on that it was decided to give you a ({{$punishmentsEn}}) hoping that it will not be repeated
                    and hoping you
                    to be on the level of responsibility and compliance with procedures and work instructions of the
                    company so
                    we don’t have to implement other penalties according to the law.
                </p>


            </div>
            <div class="flex justify-between px-20 pt-10 ">
                <p class="text-2xl">Employee signature</p>
                <p class="text-2xl">HR Department</p>
            </div>
        </page>
    </div>

</div>
</body>
</html>