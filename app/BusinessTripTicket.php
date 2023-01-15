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
        $lastLeaveService = Ticket::where('category_id', 104)->orderBy('created_at', 'DESC')->first();

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
            $ticket = $request->ticket;
            $item = Subcategory::find($ticket['subcategory_id'])->custom_fields()->where('name', 'Start Date')->first();

            $requestFields = collect(json_decode($ticket['fields']));
            $startDate = $requestFields->get($item->id);

            // check if it is between
            return Carbon::parse($startDate)->isBetween($formattedStartDate, $formattedEndDate);
        }
    }
}
