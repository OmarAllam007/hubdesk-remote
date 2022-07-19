<!doctype html>
<html lang="en">
@include('letters.layout._custom_header')
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
            </div>
            {{--            Arabic Part --}}
            <div class="flex pt-10   px-10 justify-center">
                <p class="text-center underline font-bold text-3xl">
                    شهادة خبرة وإخلاء طرف
                </p>
            </div>

            @php
                $enSponser = \App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']];
                $ar_job = $user['is_saudi'] ? $user['occupation'] :'Get it from Labour office';
                $en_job = $user['is_saudi'] ? $user['en_occupation'] :'Get it from Labour office';
                $company = 'From Labour Office';
            @endphp

            <div class="flex flex-col pt-10 px-10 pb-10 " dir="rtl">
                <p class="text-3xl">
                    <span>
                    نفيد نحن شركة {{$company}} بأن السيد / {{$user['ar_name']}} ، {{$user['ar_nationality']}} الجنسية ،

                    يحمل هوية رقم : {{$user['iqama_number']}}، قد عمل لدينا بوظيفة : {{$ar_job}}
                     من تاريخ :  {{$user['date_of_join']}} وحتى تاريخ : 08/06/2022 م ، وقد تم إخلاء طرفه وإعطائه
                    هذه الشهادة دون أدنى مسؤولية او إلتزام على الشركة .
                    </span>
                </p>

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
                            Nationality, having national No. {{$user['iqama_number']}}, Served {{$company}}
                            company as {{$en_job}} from: {{$user['date_of_join']}} to:
                            , we cleared him and has given him this certificate without any
                            obligation or responsibility to the company.
                    </span>
                </p>

                <p class="text-3xl text-center pt-10">
                    We wish him a good luck.
                </p>
            </div>

            <div class="flex-col">
                <div class="flex  pt-5  px-10 ">
                    <p class="text-3xl">
                        {{\App\Helpers\LetterSponserMap::$sponsers[$user['sponsor_id']]}}
                    </p>
                </div>

                <div class="flex justify-between">
                    <div class="w-1/3 mx-3 ">
                        <img class="w-2/3 " src="{{asset('/storage'.$letterTicket->signature)}}">
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