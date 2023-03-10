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
                    To The {{$letterTicket->ticket->fields()->first()->value}}
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
                        This is to certify that Mr. {{$user['en_name']}} {{$user['en_nationality']}}, ID Number {{$user['iqama_number']}} , Passport Number {{$user['passport_number']}} Is an
                        employee of {{\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]}}, from {{$user['date_of_join']}} up to the present.
                    </span>
                </p>
                <p class=" text-3xl ">
                    <span>
                        He is currently working as an {{$user['en_occupation']}}
                    </span>
                </p>
            </div>


            <div class="flex pt-20 px-10 ">
                <p class="text-2xl">
                    Signed By:
                </p>
            </div>


            <div class="flex justify-between  pt-5   px-10  ">
                <div class="w-1/2 flex-col">
                    <div class="flex justify-start">
                        <p class="text-2xl " dir="rtl">
                            Muhammad Abdulrahman Al-Katheer
                        </p>
                    </div>
                    <div class="flex justify-start   pt-8  ">
                        <img class="w-5/12  " src="{{asset('/storage'.$letterTicket->signature)}}">
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center pt-3  ">
                    <div class="visible-print text-center">
                        @php $ticketId  =  Crypt::encryptString($letterTicket->ticket_id);@endphp
                        {!! QrCode::size(120)->generate(route('letters.verify-letter',[$ticketId])); !!}
                    </div>
                </div>
                <br>
                <div class="w-1/2  flex-col">
                    <div>
                        <p class="text-2xl " dir="rtl">
                            {{\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]}}
                        </p>
                    </div>
                    {{--        @dd($letterTicket->stamp)--}}
                    <div class="flex justify-end pt-8 ">
                        <img class="w-5/12 " src="{{asset('/storage'.$letterTicket->stamp)}}" alt="">
                    </div>
                </div>
            </div>

        </page>
    </div>


</div>
</body>
</html>