<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;

class VacationTicket
{
    static function validateLeaveRequestIsConflictWithBusinessTripDates(Request $request)
    {
        $ticket = $request->ticket;
        $requestFields = collect(json_decode($ticket['fields']));

        $requesterID = $request->requester_id ? $request->requester_id : $requestFields[1151];

        $lastBusinessTripTicket = Ticket::where('subcategory_id', 409)
            ->where('requester_id',$requesterID)
            ->orderBy('created_at', 'DESC')->first();

        if ($lastBusinessTripTicket) {
            //get start and endDate of Business Trip
            $startDateBusinessTrip = $lastBusinessTripTicket->fields()
                ->where('name', 'Start Date')
                ->first();

            $endDateBusinessTrip = $lastBusinessTripTicket->fields()
                ->where('name', 'Date of joining the work after trip')
                ->first();

            $formattedStartDate = Carbon::parse($startDateBusinessTrip->value)->toDateString();
            $formattedEndDate = Carbon::parse($endDateBusinessTrip->value)->toDateString();


            // get start date of vacation
            $item = Item::find($ticket['item_id'])->custom_fields()->where('name', 'Vacation Start Date')->first();

            $startDate = $requestFields->get($item->id);

            // check if it is between
            return Carbon::parse($startDate)->isBetween($formattedStartDate, $formattedEndDate);
        }
    }
}
