<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BusinessTripTicket
{
    static function validateBusinessTripConflictsWithLeaveTicket(Request $request)
    {
        $ticket = $request->ticket;
        $requestFields = collect(json_decode($ticket['fields']));

        $requesterID = $request->requester_id ? $request->requester_id : auth()->id();

        $lastLeaveService = Ticket::where('category_id', 104)
            ->where('requester_id', $requesterID)
            ->orderBy('created_at', 'DESC')->first();

        if ($lastLeaveService) {
            //get start and endDate of last Leave Ticket
            $startDateBusinessTrip = $lastLeaveService->fields()
                ->where('name', 'Vacation Start Date')
                ->first();

            $endDateBusinessTrip = $lastLeaveService->fields()
                ->where('name', 'Vacation End Date')
                ->first();

            $formattedStartDate = Carbon::parse($startDateBusinessTrip->value)->toDateString();
            $formattedEndDate = Carbon::parse($endDateBusinessTrip->value)->toDateString();


            // get start date of business trip

            $item = Subcategory::find($ticket['subcategory_id'])->custom_fields()->where('name', 'Start Date')->first();

            $startDate = $requestFields->get($item->id);

            // check if it is between
            return Carbon::parse($startDate)->isBetween($formattedStartDate, $formattedEndDate);
        }
    }
}
