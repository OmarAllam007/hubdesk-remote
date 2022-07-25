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
                font-family: Arial, Helvetica, sans-serif;
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
            <div class="pt-10 px-10">
                <p class="pt-10"></p>
                <p class="text-gray-900 text-xl pt-64   ">HD: {{$letterTicket->ticket->id}}
                    {{--                Ticket No--}}
                </p>
                <p class="text-gray-900 text-xl pt-10  ">ID: {{$letterTicket->ticket->requester->employee_id}}
                    {{--            employee id--}}
                </p>
                <div class="flex">
                    <p class="text-gray-900 text-xl  pt-10   ">{{$letterTicket->ticket->requester->business_unit->name}}
                        {{--                 company assigned --}}
                    </p>
                </div>

                <div class="flex">
                    <p class="text-gray-900 text-xl  pt-10   "> Date : {{$letterTicket->last_approval_date}}</p>
                </div>
            </div>


            <div class="flex pt-10 px-10 ">
                <p class="text-2xl font-bold ">
                    His Excellency
                </p>
            </div>

            <div class="flex  pt-20 px-10">
                <p class="text-3xl">
                    {{--                    change as per Name --}}
                    To / {{$letterTicket->ticket->fields()->first()->value}}
                </p>
                <p class="px-64 "></p>
                {{--                <p class="px-10   "></p>--}}
            </div>

            <div class="flex  pt-10 px-10 ">

                <p class="text-3xl">
                    Dear Sir,
                </p>
            </div>
            <div class="flex flex-col pt-10 px-10 ">
                <p class="text-3xl">
                    <span>
                        This is to certify that Mr. {{$user['en_name']}}, {{$user['en_nationality']}}, Iqama Number {{$user['iqama_number']}}, Passport number {{$user['passport_number']}}. Is an
                        employee of {{\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]}}, from {{$user['date_of_join']}} up to the present.
                    </span>
                </p>
                <p class=" text-3xl ">
                    <span>
                        He is currently working as an {{$user['en_occupation']}}, and receiving a Total salary of ({{$user['total_package']}} SR) Monthly.
                    </span>
                </p>
            </div>

            <div class="flex pt-10 px-10 ">
                <p class="text-3xl">
                    Without any liability on the company
                </p>
            </div>

            <div class="flex justify-between">
                <div class="w-1/3 mx-3 ">
                    <img class="w-2/3 " src="{{asset('/storage'.$letterTicket->signature)}}">
                </div>
                <div class="w-1/3 mx-2 ">
                    <img class="w-1/2" src="{{asset('/storage'.$letterTicket->stamp)}}"  alt="">
                </div>
            </div>
        </page>
    </div>


</div>
</body>
</html>