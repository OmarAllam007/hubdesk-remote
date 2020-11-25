<div class="flex flex-col m-5">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            <i class="fa fa-support"></i> {{t('Resolution')}}
        </div>
        <div class="p-3">
            @foreach($ticket->notes as $note)
                <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
                    <div class="bg-gray-300 text-black px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
                        Added by {{$ticket->resolution->user->name }}
                        at {{$ticket->resolution->created_at->format('d/m/Y H:i:s')}}
                    </div>

                    <div class="p-3">
                        {!! tidy_repair_string($ticket->resolution->content,[],'utf8') !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>