<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$letterTicket->ticket->id}}</title>
    <style>
        @media print, screen {

            body {
                font-family: Arial, Helvetica, sans-serif;
                display: table;
                table-layout: fixed;
                height: auto;
            }

            table > tr > td {
                border: #1b1e21 !important;
            }

            .table-header-cell {
                background-color: #eeecec !important;
            }

            table > tr > td {
                border: #1b1e21 !important;
            }

            .table-header-cell-value {
                background-color: white !important;
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
            <div>
{{--                <p class="pt-5 "></p>--}}
                <p class="text-gray-900 text-xl pt-64  px-5  ">HD: {{$letterTicket->ticket->id}}
                    {{--                Ticket No--}}
                </p>
                <p class="text-gray-900 text-xl pt-5  px-5 ">ID: {{$letterTicket->ticket->requester->employee_id}}
                    {{--            employee id--}}
                </p>
                <div class="flex justify-between">
                    <p class="text-gray-900 text-xl  pt-5  px-5 ">{{$letterTicket->ticket->requester->business_unit->name}}
                        {{--                 company assigned --}}
                    </p>
                    <p class="text-gray-900 text-xl  pt-5   px-5 "> الموافق : {{$letterTicket->last_approval_date}}
                        م</p>
                </div>

                <div class="flex justify-center pt-5  ">
                    <p class="underline px-5 text-4xl font-bold ">
                        خطاب : تعريف بالراتب
                    </p>
                </div>

                <div class="flex  pt-5  px-10" dir="rtl">
                    <p class="text-4xl">
                        {{--                    change as per Name --}}
                        الســادة / {{$letterTicket->letter->ar_name}}
                    </p>
                    <p class="px-64 "></p>
                    <p class="px-10   "></p>
                    <p class="text-4xl">المحترمين</p>
                </div>

                <div class="flex justify-end pt-5 px-10 ">

                    <p class="text-4xl" dir="rtl">
                        السلام عليكم ورحمة الله وبركاته
                    </p>
                </div>
            </div>

            <div class="flex justify-center  pt-5  ">

                <table dir="rtl" class="w-full min-w-max table-auto table-header pl-20 pr-20 mx-20 ">
                    <tr>
                        <td class="p-3 table-header-cell font-bold text-3xl  border-2 border-black">
                            اسم الموظف
                        </td>
                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">
                            الجنسية
                        </td>
                    </tr>
                    <tr>
                        <td class="text-3xl border-2 border-black p-3  table-header-cell-value ">{{$user['ar_name']}}</td>
                        <td class="text-3xl  border-2 border-black p-3 table-header-cell-value ">{{$user['ar_nationality']}}</td>
                    </tr>

                    <tr>
                        <td class="p-3 table-header-cell font-bold text-3xl  border-2 border-black">
                            رقم إثبات الهوية
                        </td>
                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">
                            الرقم الوظيفي
                        </td>
                    </tr>
                    <tr>
                        <td class="text-3xl  border-2 border-black p-3 table-header-cell-value ">{{$user['iqama_number']}}</td>
                        <td class="text-3xl border-2 border-black p-3  table-header-cell-value ">{{$letterTicket->ticket->requester->employee_id}}</td>
                    </tr>

                    <tr>
                        <td class="p-3 table-header-cell font-bold text-3xl  border-2 border-black">
                            الوظيفة
                        </td>
                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">
                            تاريخ التعيين
                        </td>
                    </tr>
                    <tr>
                        <td class="text-3xl  border-2 border-black p-3 table-header-cell-value ">{{$user['occupation']}}</td>
                        <td class="text-3xl border-2 border-black p-3  table-header-cell-value ">{{$user['date_of_join']}}</td>
                    </tr>


                    <tr>
                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">إجمالي الراتب</td>
                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">الراتب الأساسي</td>

                    </tr>
                    <tr>
                        <td class="text-3xl  border-2 border-black p-3 table-header-cell-value ">{{$user['total_package']}}</td>
                        <td class="text-3xl border-2 border-black p-3  table-header-cell-value ">{{$user['allowances']['basic_salary']}}</td>
                    </tr>
                    <tr>
                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">
                            تفاصيل البدلات
                        </td>

                        <td class="p-3 table-header-cell  font-bold text-3xl  border-2 border-black">
                            رقم حساب الموظف
                        </td>
                    </tr>
                    <tr>
                        <td class="text-3xl  border-2 border-black p-3 table-header-cell-value ">
                            بدل سكن ( {{$user['allowances']['housing_allowance']}} ) ، بدل نقل
                            ( {{$user['allowances']['transportation_allowance']}} )
                        </td>
                        <td class="text-3xl border-2 border-black p-3  table-header-cell-value ">
                            {{$user['iban']}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="flex p-5  " dir="rtl">
                <p dir="rtl" class="text-3xl font-bold">
                    كما نفيدكم أن الموظف المذكور بياناته أعلاه لازال يعمل لدينا حتى تاريخه، وقد اعطي هذا الخطاب بناء على طلبه دون ادنى مسؤولية على الشركة.
                </p>
            </div>



            <div class="flex">
                <div class="flex  pt-5    px-10 ">
                    <p class="text-4xl" dir="rtl">
                        {{$user['sponsor_company']}}
                    </p>
                </div>
            </div>
            <div class="flex pt-5   px-10">
                <div class="flex-col  w-1/2">
                    <p class="text-4xl text-left" dir="rtl">
                        {{config('letters.signature_name')}}
                    </p>
                    <div class="w-8/12   mx-3 ">
                        <img src="{{url('/storage'.$letterTicket->signature)}}" class="w" alt="">
                    </div>
                </div>
                <div class="flex w-1/2 justify-end">
                    <div class="w-7/12   mx-2 text-right">
                        <img src="{{url('/storage'.$letterTicket->stamp)}}" class="w" alt="">
                    </div>
                </div>
            </div>
        </page>
    </div>


</div>
</body>
</html>