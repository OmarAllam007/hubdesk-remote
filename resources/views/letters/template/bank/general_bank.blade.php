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
                <p class="text-gray-900 text-xl  pt-10  px-5 "> الموافق : {{$letterTicket->last_approval_date}}
                    م</p>
            </div>

            <div class="flex justify-center pt-10 ">
                <p class="underline px-5 text-4xl font-bold ">
                    خطاب : تعريف بالراتب
                </p>
            </div>

            <div class="flex  pt-20 px-10" dir="rtl">
                <p class="text-4xl">
                    {{--                    change as per Name --}}
                    الســادة / {{$letterTicket->letter->ar_name}}
                </p>
                <p class="px-64 "></p>
{{--                <p class="px-10   "></p>--}}
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
                    نفيدكم نحن {{$user['sponsor_company']}} بأن الموظف / {{$user['ar_name']}}
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
                    ، ويتقاضى الراتـب اســاسي ({{$user['allowances']['basic_salary']}} ريال)،
                    </span>

                    <span>
                    بدل سكن ({{$user['allowances']['housing_allowance']}} ريال ) ،
                    </span>
                    <span>
                    بدل نقل ({{$user['allowances']['transportation_allowance']}} ريال) ،
                    </span>

                    @if(isset($user['allowances']['food_allowance']))
                        <span>
                    بدل طعام({{$user['allowances']['food_allowance']}}ريال)
                    </span>
                    @endif
                    @if(isset($user['allowances']['nature_of_work_allowance']))
                        <span>
                            بدل طبيعة عمل ({{$user['allowances']['nature_of_work_allowance']}} ريال) ،
                    </span>
                    @endif

                    @if(isset($user['fixed_amount']))
                        <span>
                    بدل ثابت (100ريال)
                    </span>
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
                <p class="text-4xl font-bold" dir="rtl">
                    ولكم وافر الشكر والتقدير ،،،
                </p>
            </div>


            <div class="flex-col">
                <div class="flex  pt-10   px-10 ">
                    <p class="text-4xl" dir="rtl">
                        {{$user['sponsor_company']}}
                    </p>
                </div>

                <div class="flex  pt-5   px-10 ">
                    <p class="text-4xl" dir="rtl">
                        {{config('letters.signature_name')}}
                    </p>
                </div>

                <div class="flex justify-between">
                    <div class="w-1/3 mx-3 ">
                        <img src="{{asset('/stamps/8/signature.png')}}" class="w" alt="">
                    </div>
                    <div class="w-1/3 mx-2 ">
                        <img src="{{asset('/stamps/8/stamp_test.png')}}" class="w" alt="">
                    </div>
                </div>
            </div>


            <div class="flex justify-center">

            </div>
        </page>
    </div>


</div>
</body>
</html>