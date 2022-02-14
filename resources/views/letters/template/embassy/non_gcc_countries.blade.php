<!doctype html>
<html lang="en">
@include('letters.layout._header')
<body>
<div class="flex">
    <div class="">
        <page size="A4" class="">
            <div class="pt-10 px-10">
                <p class="pt-20 "></p>

                <p class="text-gray-900 text-xl pt-64">ID: {{$letterTicket->ticket->requester->employee_id}}
                </p>
                <p class="text-gray-900 text-xl pt-5">HD: {{$letterTicket->ticket->id}}
                </p>

                <div class="flex">
                    <p class="text-gray-900 text-xl  pt-5"> Date : {{$letterTicket->last_approval_date}}</p>
                </div>
            </div>

            <div class="flex pt-10   px-10 ">
                <p class="lt_text  ">
                    His Excellency
                </p>
            </div>

            <div class="flex pt-5 px-10 ">
                <p class="lt_text  ">
                    The Consul General
                </p>
            </div>

            <div class="flex  pt-10 px-10">
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
                        employee of {{\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]}}, from {{$user['date_of_join']}} up to the present.
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
                    This certification is issued upon the request of the afore-mentioned party.
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
                        {{\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]}}
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
            </div>

        </page>
    </div>


</div>
</body>
</html>