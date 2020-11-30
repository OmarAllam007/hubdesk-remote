<div class="flex flex-col m-5">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            <i class="fa fa-comments-o"></i> {{t('Conversations')}}
        </div>
        <div class="p-3">
            @foreach($ticket->replies()->latest()->get() as $reply)
                <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
                    <div class="bg-gray-300 text-black px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
                        <i class="fa fa-comments-o"></i> {{t('By')}}
                        : {{$reply->user->name}}
                        On {{$reply->created_at->format('d/m/Y H:i A')}}
                    </div>

                    <div class="p-3">
                        {!! tidy_repair_string($reply->content, [], 'utf8') !!}
                    </div>

                    <div class="p-3 flex justify-end">
                        <span class="label label-default">Status: {{t($reply->status->name)}}</span>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>