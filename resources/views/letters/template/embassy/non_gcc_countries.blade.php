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
            <div class="pt-10 px-10">
                <p class="pt-10"></p>
                <p class="text-gray-900 text-xl pt-10">ID: {{$letterTicket->ticket->requester->employee_id}}
                </p>
                <p class="text-gray-900 text-xl pt-64">HD: {{$letterTicket->ticket->id}}
                </p>

                <div class="flex">
                    <p class="text-gray-900 text-xl  pt-10   "> Date : {{$letterTicket->last_approval_date}}</p>
                </div>
            </div>

            <div class="flex pt-10   px-10 ">
                <p class="text-2xl ">
                    His Excellency
                </p>
            </div>

            <div class="flex pt-5 px-10 ">
                <p class="text-2xl ">
                    The Consul General
                </p>
            </div>

            <div class="flex  pt-20 px-10">
                <p class="text-3xl">
                    {{--                    change as per Name --}}
                    To The {{$letterTicket->ticket->fields()->first()->value}} Embassy
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
                        This is to certify that Mr. {{$user['en_name']}} {{$user['en_nationality']}}, ID Number {{$user['iqama_number']}}. Is an
                        employee of {{$user['en_sponsor_company']}}, from {{$user['date_of_join']}} up to the present.
                    </span>
                </p>
                <p class=" text-3xl ">
                    <span>
                        He is currently working as an {{$user['en_occupation']}}, and receiving a Total salary of ({{$user['total_package']}} SR) Monthly.
                    </span>
                </p>
                @php
                    $reason = $letterTicket->ticket->fields->last() ? $letterTicket->ticket->fields->last()->value : '';
                @endphp

                <div class="text-3xl">
                    We hereby certify that the above-mentioned will be visiting your country to {{$reason}}.
                    This certification is issued upon the request of the afore-mentioned party for visa application
                    purposes.
                    Without any liability to the company
                </div>
            </div>


            <div class="flex pt-20 px-10 ">
                <p class="text-3xl">
                    Signed By:
                </p>
            </div>


            <div class="flex-col">
                <div class="flex  pt-5  px-10 ">
                    <p class="text-3xl">
                        {{$user['en_sponsor_company']}}
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