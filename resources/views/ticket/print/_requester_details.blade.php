<div class="flex flex-col m-5">
    <div class="mb-2 border-solid border-grey-light rounded border shadow-sm rounded-lg shadow-lg">
        <div class="bg-blue-600 text-white px-2 py-3 border-solid border-grey-light border-b  rounded-t-lg">
            <i class="fa fa-user"></i> {{t('Requester Details')}}
        </div>
        <div class="p-3">
            <table class="min-w-full table-auto">
                <tr>
                    <th class="text-left w-1/5">{{t('Name')}}</th>
                    <td class="text-left w-1/5">{{$ticket->requester->name}}</td>
                    <th class="text-left w-1/5">{{t('Business Unit')}}</th>
                    <td class="text-left w-1/5">{{$ticket->requester->business_unit->name ?? 'Not Assigned'}}</td>
                </tr>
                <tr>
                    <th class="text-left w-1/5">{{t('Email')}}</th>
                    <td class="text-left w-1/5">{{$ticket->requester->email ?? 'Not Assigned'}}</td>
                    <th class="text-left w-1/5">{{t('Location')}}</th>
                    <td class="text-left w-1/5">{{$ticket->requester->location->name ?? 'Not Assigned'}}</td>
                </tr>
                <tr>
                    <th class="text-left w-1/5">{{t('Phone')}}</th>
                    <td class="text-left w-1/5">{{$ticket->requester->phone ?? 'Not Assigned'}}</td>
                    <th class="text-left w-1/5">{{t('Mobile')}}</th>
                    <td class="text-left w-1/5">{{$ticket->requester->mobile ?? 'Not Assigned'}}</td>
                </tr>
            </table>

        </div>
    </div>
</div>