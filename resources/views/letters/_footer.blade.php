<div class="flex justify-between  pt-5   px-20  ">
    <div class="w-1/2 flex-col">
        <div class="flex justify-start">
            <p class="text-2xl " dir="rtl">
                {{config('letters.signature_name')}}
            </p>
        </div>
        <div class="flex justify-start   pt-8  ">
            <img class="w-5/12  " src="{{asset('/storage'.$letterTicket->signature)}}">
        </div>
    </div>
    <div class="flex flex-col justify-center items-center pt-16 ">
        <p class="pt-20"></p>

        <div class="visible-print text-center">
            @php $ticketId  =  Crypt::encryptString($letterTicket->ticket_id);@endphp
            {!! QrCode::size(120)->generate(route('letters.verify-letter',[$ticketId])); !!}
        </div>
    </div>
    <br>
    <div class="w-1/2  flex-col">
        <div>
            <p class="text-2xl " dir="rtl">
                {{$user['sponsor_company']}}
            </p>
        </div>
        {{--        @dd($letterTicket->stamp)--}}
        <div class="flex justify-end pt-8 ">
            <img class="w-5/12 " src="{{asset('/storage'.$letterTicket->stamp)}}" alt="">
        </div>
    </div>
</div>