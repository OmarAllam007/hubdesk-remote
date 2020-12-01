<div class="flex flex-col m-5">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            <i class="fa fa-sticky-note-o"></i> {{t('Notes')}}
        </div>
        <div class="p-3">
            @foreach($ticket->notes as $note)
                <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
                    <div class="bg-gray-300 text-black px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
                        {{t('By: ') . $note->creator->name}}
                        {{t('On ').$note->created_at->format('d/m/Y H:i A') }}
                    </div>

                    <div class="p-3">
                        {!! tidy_repair_string($note->note, [], 'utf8') !!}

                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>