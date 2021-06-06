<div class="flex flex-col m-5">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            <i class="fa fa-check"></i> {{t('Approvals')}}
        </div>
        <div class="p-3">
            @foreach($ticket->approvals as $approval)
                <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
                    <div class="bg-gray-300 text-black px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
                        {{t('By: ') . $approval->created_by->name}}
                        {{t('On ').$approval->created_at->format('d/m/Y H:i A') }} {{t('To')}} {{$approval->approver->name}}
                    </div>

                    <div class="p-3">
                        {!! tidy_repair_string($approval->content, [], 'utf8') !!}

                    </div>

                    <div class="p-3 flex">
                        @if($approval->comment)
                            {{--                            <div class="p-3 flex justify-end">--}}
                            <strong>
                            <span class="label label-default">Comment: {!! $approval->comment !!}</span>
                            </strong>
                            {{--                            </div>--}}
                        @endif
                        <div class="flex justify-end">

                            <span class="label label-default">

                                  Status: {{App\TicketApproval::$statuses[$approval->status]}}
                            </span>
                        </div>

                    </div>




                </div>
            @endforeach
        </div>
    </div>
</div>