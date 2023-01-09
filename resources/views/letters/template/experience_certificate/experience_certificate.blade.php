<!doctype html>
<html lang="en">
@include('letters.layout._custom_header')
<style>
    div {
        text-align: justify;
        text-justify: inter-word;
    }
</style>
<body>

<div class="flex">
    <div class="">
        <page size="A4" class="">
            <div class="pt-10 px-10">
                <p class="pt-20 "></p>
                <p class="text-gray-900 text-xl pt-64">ID: {{$employeeID->value}}
                </p>
                <p class="text-gray-900 text-xl pt-5">HD: {{$letterTicket->id}}
                </p>
                <p class="flex pt-10   px-10 justify-center">Request for Testing Purpose</p>
            </div>
            {{--            Arabic Part --}}
            <div class="flex pt-10   px-10 justify-center">
                <p class="text-center underline font-bold text-3xl">
                    شهادة خبرة وإخلاء طرف
                </p>
            </div>


            @php
                $labourUser = \App\LabourOfficeUser::where('employee_id',$user['employee_id'])->first();
                $enSponser = \App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']];
                $ar_job = $user['is_saudi'] ? $user['occupation'] : $labourUser->job;
                $en_job = $user['is_saudi'] ? $user['en_occupation'] :
                \App\LetterJobMap::where('ar_name',$ar_job)->first()->en_name ?? '';
                $ar_company = $labourUser->building_name;
                $en_company = \App\LabourOfficeCompanyMap::where('ar_name','like','%'.$labourUser->building_name.'%')->first()->en_name ?? '';
                $toDate = $letterTicket->fields()->where('name','Last working day')->first()->value;

                $arDescription = $letterTicket->fields()->where('name','Arabic Job Description')->first();
                $enDescription = $letterTicket->fields()->where('name','English Job Description')->first();

                $arabicJobDescription = $arDescription ? $arDescription->value :  '';
                $englishJobDescription =  $enDescription? $enDescription->value : '';

            @endphp

            <div class="flex flex-col pt-10 px-10 pb-10 " dir="rtl">
                <p class="text-3xl">
                    <span>
                    نفيد نحن شركة {{$ar_company}} بأن السيد / {{$user['ar_name']}} ، {{$user['ar_nationality']}} الجنسية ،

                    يحمل هوية رقم : {{$user['iqama_number']}}، قد عمل لدينا بوظيفة : {{$ar_job}}
                     من تاريخ :  {{$user['date_of_join']}} م وحتى تاريخ : {{$toDate}} م ، وقد تم إخلاء طرفه وإعطائه
                        @if($arabicJobDescription != '')
                            وقد عمل الموظف لدينا بالمهام التالية : {{$arabicJobDescription}} .
                        @endif
                    </span>
                </p>
                <p class="text-3xl" dir="rtl">هذه الشهادة دون أدنى مسؤولية او إلتزام على الشركة .</p>

                <p class="text-3xl text-center pt-10">
                    تمنياتنا له بدوام التوفيق ،،،
                </p>
            </div>

            <hr class="pt-10 ">


            <div class="flex pt-10   px-10 justify-center">
                <p class="text-center underline font-bold text-3xl">
                    Clearance and Experience Certificate
                </p>
            </div>
            <div class="flex flex-col pt-10 px-10 ">
                <p class="text-3xl">
                    <span>
                            We would like to inform you that Mr. {{$user['en_name']}}, {{$user['en_nationality']}}
                            Nationality, having national No. {{$user['iqama_number']}}, Served {{$en_company}}
                            company as {{$en_job}} from: {{$user['date_of_join']}} to: {{$toDate}}
                        @if($englishJobDescription != '')
                            and he is doing the following tasks: {{$englishJobDescription}} .
                        @endif
                    </span>
                </p>
                <p class="text-3xl">
                    we cleared him and has given him this certificate without any
                    obligation or responsibility to the company.
                </p>

                <p class="text-3xl text-center pt-10">
                    We wish him a good luck.
                </p>
            </div>

            <div class="flex-col">
                <div class="  pt-5  px-10 ">
                    <p class="text-3xl">
                        الموارد البشرية
                    </p>
                    <p class="text-3xl">
                        Human Resources
                    </p>
                </div>

                <div class="flex justify-between">
                    <div class="w-1/3 mx-3 ">
                        {{--                        <img class="w-2/3 " src="{{asset('/storage'.$letterTicket->signature)}}">--}}
                    </div>
                    <div class="w-1/3 mx-2 ">
                        <img class="w-1/2" src="{{asset('/storage'.$letterTicket->stamp)}}" alt="">
                    </div>
                </div>
            </div>

        </page>
    </div>
</div>
</body>
</html>