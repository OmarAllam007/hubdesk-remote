<div class="flex justify-between p-5">
    <div class="w-1/2 flex-col">
        <div class="flex justify-start">
            <p class="text-4xl" dir="rtl">
                {{config('letters.signature_name')}}
            </p>
        </div>
        <div class="flex justify-start   pt-10 ">
            <img class="w-2/3 " src="{{asset('/storage'.$letterTicket->signature)}}">
        </div>
    </div>

    <div class="w-1/2  flex-col">
        <div>
            <p class="text-4xl" dir="rtl">
                {{$user['sponsor_company']}}
            </p>
        </div>
        <div class="flex justify-end">
            <img class="w-1/2" src="{{asset('/storage'.$letterTicket->stamp)}}"  alt="">
        </div>
    </div>
</div>