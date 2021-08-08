<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Letter</title>
    <style>
        @media print {

        }

        @media print {
            body {
                display: table;
                table-layout: fixed;
                /*padding-top:2.5cm;*/
                /*padding-bottom:2.5cm;*/
                height: auto;
            }
        }

        page {
            /*background: white;*/
            display: block;
            margin: 0 auto;
            /*margin-bottom: 0.5cm;*/
            /*box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);*/
            background-repeat: no-repeat;
            background-image: url({{url('/storage/headers/8/kre.jpg')}}) !important;
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
            <p class="text-gray-900 text-xl pt-64  px-5 ">HD: Ticket No
                {{--                Ticket No--}}
            </p>
            <p class="text-gray-900 text-xl pt-10 px-5 ">ID: 90000939
                {{--            employee id--}}
            </p>
            <div class="flex justify-between">
                <p class="text-gray-900 text-xl  pt-10  px-5 ">Hubtech
                    {{--                 company assigned --}}
                </p>
                <p class="text-gray-900 text-xl  pt-10  px-5 "> الموافق : {{\Carbon\Carbon::now()->format('d/m/Y')}}
                    م</p>
            </div>

            <div class="flex justify-center pt-10 ">
                <p class=" underline px-5 text-4xl font-bold ">
                    {{--                    change as per Type --}}
                    خطاب : تعريف بالراتب
                </p>
            </div>

            <div class="flex  pt-20 px-10" dir="rtl">
                <p class="text-4xl">
                    {{--                    change as per Name --}}
                    الســادة / البنك الاهلي
                </p>
                <p class="px-64 "></p>
                <p class="px-10   "></p>
                <p class="text-4xl">المحترمين</p>
            </div>

            <div class="flex justify-end pt-10 px-10 ">

                <p class="text-4xl" dir="rtl">
                    السلام عليكم ورحمة الله وبركاته ،،، وبعد ،،،
                </p>
            </div>
            <div class="flex flex-col pt-10 px-10 ">
                <p dir="rtl" class="text-3xl">
                    {{--                    change as per SAP --}}
                    نفيدكم نحن شركة الكفاح العقارية بأن الموظف / عمر خالد متولي جرنه ، الجنسية /مصري، هوية رقم/
                    2397400082،
                </p>
                <p dir="rtl" class=" text-3xl ">
                    {{--                    change as per SAP --}}
                    يعمل لدينا من تاريخ: 17/07/2016 م بوظيفة: فني شبكات حاسب ، ويتقاضى الراتـب اســاسي (1500ريال)،
                </p>
                <p dir="rtl" class="text-justify text-3xl ">
                    {{--                    change as per SAP --}}
                    بدل سكن (100ريال) ، بدل نقل (100ريال) ، بدل طعام(100ريال) بدل طبيعة عمل (100
                    ريال) ، بدل ثابت (100ريال) راتــب إجمـالي (100ريال) ، وقـد أعطــي هـــذا الخطـاب
                    بنــاءً علــى طلبـه دون أدنى مسؤوليـة على الشــركة.
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
                        {{--                        business unit--}}
                        شركة الكفاح العقارية
                    </p>
                </div>

                <div class="flex  pt-5   px-10 ">
                    <p class="text-4xl" dir="rtl">
                        {{--                        signature --}}
                        أ. سعيد الهاجري
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