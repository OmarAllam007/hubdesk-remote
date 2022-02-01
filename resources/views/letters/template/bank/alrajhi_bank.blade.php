<!doctype html>
<html lang="en">
@include('letters.layout._header')
<body>
<div class="flex">
    <div class="">
        <page size="A4" class="">
            <div>
                <p class="pt-10"></p>
                <p class="text-gray-900 text-xl pt-64  px-20 ">HD: {{$letterTicket->ticket->id}}
                    {{--                Ticket No--}}
                </p>
                <p class="text-gray-900 text-xl pt-5  px-20  ">ID: {{$letterTicket->user->employee_id ?? $letterTicket->ticket->requester->employee_id}}
                    {{--            employee id--}}
                </p>
                <div class="flex justify-between">
                    <p class="text-gray-900 text-xl  pt-5  px-20  ">{{$letterTicket->ticket->requester->business_unit->name}}
                        {{--                 company assigned --}}
                    </p>
                    <p class="text-gray-900 text-xl  pt-5   px-20  "> الموافق : {{$letterTicket->last_approval_date}}
                        م</p>
                </div>

                <div class="flex justify-center pt-5  ">
                    <p class="underline px-20  lt_text font-bold ">
                        خطاب : تعريف بالراتب
                    </p>
                </div>

                <div class="flex  pt-5  px-20" dir="rtl">
                    <p class="lt_text">
                        {{--                    change as per Name --}}
                        الســادة / {{$letterTicket->letter->ar_name}}
                    </p>
                    <p class="px-64 "></p>
                    <p class="px-20    "></p>
                    <p class="lt_text">المحترمين</p>
                </div>

                <div class="flex justify-end pt-2  px-20">

                    <p class="lt_text" dir="rtl">
                        السلام عليكم ورحمة الله وبركاته
                    </p>
                </div>
            </div>

            <div class="flex justify-center  pt-5">
                <table dir="rtl" class="w-full min-w-max table-auto table-header pl-20 pr-20 mx-20 ">
                    <tr>
                        <td class="p-3 table-header-cell font-bold lt_text   border-2 border-black">
                            اسم الموظف
                        </td>
                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">
                            الجنسية
                        </td>
                    </tr>
                    <tr>
                        <td class="lt_text  border-2 border-black p-3  table-header-cell-value ">{{$user['ar_name']}}</td>
                        <td class="lt_text   border-2 border-black p-3 table-header-cell-value ">{{$user['ar_nationality']}}</td>
                    </tr>

                    <tr>
                        <td class="p-3 table-header-cell font-bold lt_text   border-2 border-black">
                            رقم إثبات الهوية
                        </td>
                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">
                            الرقم الوظيفي
                        </td>
                    </tr>
                    <tr>
                        <td class="lt_text   border-2 border-black p-3 table-header-cell-value ">{{$user['iqama_number']}}</td>
                        <td class="lt_text  border-2 border-black p-3  table-header-cell-value ">{{$letterTicket->user->employee_id ?? $letterTicket->ticket->requester->employee_id}}</td>
                    </tr>

                    <tr>
                        <td class="p-3 table-header-cell font-bold lt_text   border-2 border-black">
                            الوظيفة
                        </td>
                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">
                            تاريخ التعيين
                        </td>
                    </tr>
                    <tr>
                        <td class="lt_text   border-2 border-black p-3 table-header-cell-value ">{{$user['occupation']}}</td>
                        <td class="lt_text  border-2 border-black p-3  table-header-cell-value ">{{$user['date_of_join']}}</td>
                    </tr>


                    <tr>
                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">إجمالي الراتب</td>
                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">الراتب الأساسي
                        </td>

                    </tr>
                    <tr>
                        <td class="lt_text   border-2 border-black p-3 table-header-cell-value ">{{$user['total_package']}}</td>
                        <td class="lt_text  border-2 border-black p-3  table-header-cell-value ">{{$user['allowances']['basic_salary']}}</td>
                    </tr>
                    <tr>
                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">
                            تفاصيل البدلات
                        </td>

                        <td class="p-3 table-header-cell  font-bold lt_text   border-2 border-black">
                            رقم حساب الموظف
                        </td>
                    </tr>
                    <tr>
                        <td class="lt_text   border-2 border-black p-3 table-header-cell-value ">
                            بدل سكن ( {{$user['allowances']['housing_allowance']}} )
                            @if(isset($user['allowances']['transportation_allowance']))
                                ، بدل نقل  ( {{$user['allowances']['transportation_allowance']}} )
                            @endif
                            @if(isset($user['allowances']['nature_of_work_allowance']))
                                ، بدل طبيعة عمل  ( {{$user['allowances']['nature_of_work_allowance']}} )
                            @endif

                            @if(isset($user['allowances']['food_allowance']))
                                ، بدل طعام  ( {{$user['allowances']['food_allowance']}} )
                            @endif

                            @if(isset($user['allowances']['fixed_overtime']))
                                ، بدل إضافي ثابت  ( {{$user['allowances']['fixed_overtime']}} )
                            @endif

                            @if(isset($user['allowances']['fixed_bonus']))
                                ، بدل  ثابت  ( {{$user['allowances']['fixed_bonus']}} )
                            @endif
                        </td>
                        <td class="lt_text  border-2 border-black p-3  table-header-cell-value ">
                            {{$user['iban']}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="flex pt-5   px-20 " dir="rtl">
                <p dir="rtl" class="lt_text ">
                    كما نفيدكم أن الموظف المذكور بياناته أعلاه لازال يعمل لدينا حتى تاريخه، وقد اعطي هذا الخطاب بناء على
                    طلبه دون ادنى مسؤولية على الشركة.
                </p>
            </div>


            @include('letters._footer')
            @include('letters.template._footer_query')
        </page>
    </div>


</div>
</body>
</html>