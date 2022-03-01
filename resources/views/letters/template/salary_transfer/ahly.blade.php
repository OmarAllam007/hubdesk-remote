<html lang="en">
@include('letters.template._header')


<body>
<div class="flex">
    <div class="">
        <page size="A4" class="" style="font-size: 10pt;">
            <div class="pt-32"></div>
            <div class="pt-32 "></div>
            <div class="pt-10  "></div>
            <div class="pt-20 px-10 "></div>
            <div class="flex px-5 ">
                <div class="w-1/2 border border-black p-2 ">
                    <div class="flex flex-col">
                        <p>Date : {{$letterTicket->last_approval_date}}</p>
                        <p class="pt-2 ">M/S: NCB</p>
                        <p class="pt-2 ">Subject: Salary Transfer Certificate</p>
                        <p class="pt-2 ">This is to certify that the below mentioned is our full time employee and based on authorization from him we will transfer his net monthly salary along with all other allowances and commissions (if any) regularly on a monthly basis to: and shall also deposit his final settlement including End of Service Benefits and any other allowances in case of his resignation, retirement or termination due to any reason in to his bank account at: Bank: NCB
                        </p>
                        <p class="pt-2  ">A/C No: {{$user['iban']}}</p>

                        <p class="pt-2  ">
                            IBAN NO: {{$user['iban']}}
                        </p>

                        <p class="pt-2 ">Also the company certifies that it will not cancel this undertaking unless a written clearance from
                            Bank is received, moreover, in case of resignation / retirement/ termination, the company will immediately inform NCB's collections Department at Al- Khaldiya, Jeddah P.O box 19396 Jeddah 21435 or by e-mail CollectionsSkipThacing@alahli.com about the date of deposit of final settlement of this employee. In this regard, the company is not responsible for any legal or financial commitment except what is mentioned above.
                        </p>
                        <p class="pt-2 ">
                            Employee Name: {{$user['en_name']}}
                        </p>
                        <p class="pt-2 ">
                            ID NO: {{$user['iqama_number']}}
                        </p>
                        <p class="pt-2 ">
                            Hire Date: {{$user['date_of_join']}}
                        </p>

                        <p class="pt-2 ">
                            Job description: {{$user['en_occupation']}}
                        </p>

                        <p class="pt-2 ">
                            Net monthly Salary: {{$user['total_package']}} SR
                        </p>
                        <p class="pt-2 ">
                            Salary Deposit Date: First day of every month.
                        </p>

                        <p class="text-center pt-2   ">
                            {{\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]}}
                        </p>
                    </div>
                </div>
                <div class="w-1/2 border border-black p-2 " dir="rtl">
                    <p>التاريخ : {{$letterTicket->last_approval_date}}</p>
                    <p class="pt-2  ">السادة: بنك الاهلي السعودي</p>
                    <p class="pt-2  ">الموضوع: شهادة تحويل راتب</p>
                    <p class="pt-2  ">نشهد بان المذكور ادناه يعمل لديها بنظام التفرغ الكامل وبناء على تفويض منه فسوف يتم تحويل صافي راتبه مع كامل الدبلات والعمولات الشهرية (إن وجدت) وجميع مستحقاته النهائية( شاملة مكافاة نهاية الخدمة واي بدلات بعد الاستقالة) في حالة الاستقالة او الفصل او التقاعد (لأي سبب كانت الاستقالة او الفصل او التقاعد)</p>
                    <p class="pt-2  ">الى حسابه المصرفي لدى:
                        البنك الاهلي التجاري
                    </p>
                    @php
                        $IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';
                    @endphp
                    <p class="pt-2  ">
                        رقم الحساب: {{$user['iban']}}
                    </p>


                    <p class="pt-2  ">
                        رقم الحساب الدولي: {{$user['iban']}}
                    </p>
                    <p class="pt-2 ">
                         كما تلتزم الشركة بعدم السماح للموظف بالغاء او تعديل التفويض الا بموافقة البنك الخطية، هذا بالاضافة انه في حالة استقالة الموظف او فصله او تقاعده فان الشركة تلتزم باخطار البنك الاهلي السعودي (ادارة التحصيل بحي الخالدية بجدة) او بواسطة البريد الالكتروني CollectionsSkipThacing@alahli.com
                        بتاريخ ايداع مستحقاته النهائية.
                    </p>

                    <p class="pt-2 ">
                        كما ان الشركة لا تتحمل اي التزام قانوني او مالي بخلاف ما ذكر اعلاه
                    </p>
                    <p class="pt-2 ">
                        اسم الموظف: {{$user['ar_name']}}
                    </p>
                    <p class="pt-2 ">
                        رقم الهوية: {{$user['iqama_number']}}
                    </p>

                    <p class="pt-2 ">
                        تاريخ التعيين: {{$user['date_of_join']}}
                    </p>

                    <p class="pt-2 ">
                        المسمى الوظيفي: {{$user['occupation']}}
                    </p>

                    <p class="pt-2 ">
                        صافي الراتب الشهري: {{$user['total_package']}} ريال
                    </p>
                    <p class="pt-2 ">
                        تاريخ ايداعه: في اليوم الاول من كل شهر
                    </p>
                    <p class="text-center pt-2    ">
                        {{$user['sponsor_company']}}
                    </p>
                </div>
            </div>

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

                    @if($letterTicket->group->id == \App\Letter::SALARY_TRANSFER_TYPE)
                        <span dir="rtl">مستحقات نهاية الخدمة (حتى تاريخه):</span>
                        <span>{{$user['eos_amount']}}  ريال</span>
                    @endif

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