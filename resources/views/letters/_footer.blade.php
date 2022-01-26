<div class="flex justify-between  pt-10  px-20  ">
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

    <div class="w-1/2  flex-col">
        <div>
            <p class="text-2xl " dir="rtl">
                {{$user['sponsor_company']}}
            </p>
        </div>
{{--        @dd($letterTicket->stamp)--}}
        <div class="flex justify-end pt-8 ">
            <img class="w-5/12 " src="{{asset('/storage'.$letterTicket->stamp)}}"  alt="">
        </div>
    </div>
</div>