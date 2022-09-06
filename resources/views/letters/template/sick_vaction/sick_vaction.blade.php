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
            <p class="text-gray-900 text-xl pt-64  px-5 ">HD: {{$letterTicket->ticket->id}}
                {{--                Ticket No--}}
            </p>
            <p class="text-gray-900 text-xl pt-10 px-5 ">ID: {{$letterTicket->ticket->requester->employee_id}}
                {{--            employee id--}}
            </p>
            <div class="flex justify-between">
                <p class="text-gray-900 text-xl  pt-10  px-5 ">{{$letterTicket->ticket->requester->business_unit->name}}
                    {{--                 company assigned --}}
                </p>
                <p class="text-gray-900 text-xl  pt-10  px-5" dir="rtl"> التاريخ : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>
            <div class="flex  pt-20 px-10" dir="rtl">
                <p class="text-4xl">
                    @php
                        $to = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';
                    @endphp
                    الســادة /  {{$to}}
                </p>
                <p class="px-48 "></p>
                <p class="text-4xl">المحترمين</p>
            </div>

            <div class="flex justify-end pt-10 px-10 ">

                <p class="text-4xl" dir="rtl">
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
                    يعمل لدينا ولا يزال على رأس العمل حتى تاريخه
                    </span>
                    <span>
                   حيث ان المذكور راجع المستشفى / المستوصف لوعكه صحية ،ارجو تزويدنا بتقرير طبي يوضح الحاله الصحية له وتزويدنا ب  اجازة  مرضية
                    </span>
                </p>
            </div>

            <div class="flex justify-center pt-20 ">
                <p class="text-4xl font-bold" dir="rtl">
                    ولكم جزيل الشكر والتقدير ،،،
                </p>
            </div>


            @include('letters._footer')
            @include('letters.template._footer_query')
        </page>
    </div>

</div>
</body>
</html>