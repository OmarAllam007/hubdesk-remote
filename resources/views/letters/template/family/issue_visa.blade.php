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
            <p class="pt-10"></p>
            <p class="pt-64"></p>
            <p class="pt-10"></p>
            <div class="flex justify-end ">
                <p class="text-gray-900 text-xl  pt-10  px-5 "> التاريخ : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>
            @php
            $region = $letterTicket->ticket->fields->where('name','Region')->first() ? $letterTicket->ticket->fields->where('name','Region')->first()->value : '';
            $istiqdamTo = $letterTicket->ticket->fields->where('name','Request For')->first() ? $letterTicket->ticket->fields->where('name','Request For')->first()->value : '';

            $education = $letterTicket->ticket->fields->where('name','Academic Qualification')->first() ?? '';
            $specialization = $letterTicket->ticket->fields->where('name','Specialization')->first() ?? '';
            @endphp

            <div class="flex  pt-20 px-10" dir="rtl">
                <p class="text-4xl">
                    إدارة شئون الإستقدام بـ{{$region ?? ""}}
                </p>
                <p class="px-48 "></p>
                {{--                <p class="px-10   "></p>--}}
                <p class="text-4xl">المحترمين</p>
            </div>

            <div class="flex justify-center pt-10 px-10 ">
                <p class="text-4xl" dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،،
                </p>
            </div>

            <div class="flex flex-col pt-10 px-10 ">
                <p dir="rtl" class="text-3xl">
                    نود إفادة سعادتكم ان موظفنا المدعو / {{$user['ar_name']}}
                    ، الجنسية / {{$user['ar_nationality']}} والذي
                    يعمل لدينا بمهنة / {{$user['occupation']}}
                </p>

                <p dir="rtl" class="text-3xl">
                    حسب المهنة الموضحة بالاقامة رقم/ {{$user['iqama_number']}} ، والذي يتقاضى راتب شهريا
                    ( {{$user['total_package']}} ريال ) ولديه مؤهل
                    {{$education->value}} تخصص {{$specialization->value}}.
                </p>

                <p dir="rtl" class="text-3xl">
                    يرغب في استقدام /  {{$istiqdamTo ?? ""}} وحيث لا مانع لدينا من ذلك حيث ان العلاج والسكن مؤمن
                    للمذكور ،
                    عليه نأمل الموافقة على طلب موظفنا كما نصادق على صحة المعلومات الموضحة بخطابنا هذا ، وهذا إقرار منا
                    بذلك .

                </p>
            </div>


            <div class="flex justify-center pt-20 ">
                <p class="text-4xl font-bold" dir="rtl">
                    ولكم جزيل الشكر ،،،
                </p>
            </div>

            @include('letters._footer')
            @include('letters.template._footer_query')

            <div class="flex justify-center">

            </div>
        </page>
    </div>

</div>
</body>
</html>