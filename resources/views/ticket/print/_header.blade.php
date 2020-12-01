<div class="flex flex-col m-5">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            {{t('Required Information')}}
        </div>
        <div class="p-3">
            <div class="flex justify-start">
                <div>
                    <label>
                        <input type="checkbox" id="request-details" checked
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded mr-1">
                        {{t('Request Details')}}
                    </label>
                </div>

                <div class="ml-5">
                    <label>
                        <input type="checkbox" id="requester-details" checked
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded mr-1">
                        {{t('Requester Details')}}
                    </label>
                </div>
                @if ($ticket->replies->count())
                    <div class="ml-5">
                        <label>
                            <input type="checkbox" id="request-conversation" checked
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded mr-1">
                            {{t('Conversations')}}
                        </label>
                    </div>
                @endif
                @if ($ticket->approvals->count())
                    <div class="ml-5">
                        <label>
                            <input type="checkbox" id="request-approvals" checked
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded mr-1">
                            {{t('Approvals')}}
                        </label>
                    </div>
                @endif

                @if ($ticket->resolution)
                    <div class="ml-5">
                        <label>
                            <input type="checkbox" id="request-resolution" checked
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded mr-1">
                            {{t('Resolution')}}
                        </label>
                    </div>
                @endif

                @if ($ticket->notes->count())
                    <div class="ml-5">
                        <label>
                            <input type="checkbox" id="request-notes" checked
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded mr-1">
                            {{t('Notes')}}
                        </label>
                    </div>
                @endif


                <div class="flex justify-end">
                    <a class="py-2 px-4 border border-transparent shadow-sm
                text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                       id="printTicket" target="_parent">
                        <i class="fa fa-print"></i> {{t('Print')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>