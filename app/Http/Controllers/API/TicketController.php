<?php


namespace App\Http\Controllers\API;


use App\Attachment;
use App\CustomField;
use App\Helpers\Ticket\TicketViewScope;
use App\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class TicketController
{
    function index()
    {
        if (\request('clear')) {
            \Session::remove('ticket.filter');
        }

        $ticketScope = $this->handleTicketsScope();
        $query = $ticketScope['query'];
        $scope = $ticketScope['scope'];
        $scopes = $ticketScope['scopes'];
        $criterions = $ticketScope['criterions'];


        if ($search = \request('search')) {
            $ticket = Ticket::where('id', intval($search))->first();

            if ($ticket) {
                return compact('ticket', 'scope', 'scopes');
            } else {
                $user = auth()->user();
                $searchedTickets = Ticket::query()->with('requester');

                if ($user->isSupport()) {
                    $searchedTickets = $searchedTickets
                        ->orWhereHas('requester', function (Builder $query) use ($search) {
                            $query->where('employee_id', $search)
                                ->orWhere('name', 'LIKE', "%${search}%");
                        })->whereIn('group_id', $user->groups->pluck('id'));
                } else {
                    if ($search == $user->employee_id) {
                        $searchedTickets = $searchedTickets->where('requester_id', $user->id)
                            ->orWhere('creator_id', $user->id);
                    } else {
                        $searchedTickets = collect();
                    }
                }
            }

//            if ($searchedTickets->count() > 1) {
            $query = $searchedTickets;
//            }
        }


        $tickets = $query->latest('id')->paginate();

        $tickets->getCollection()->transform(function (Ticket $ticket) {
            return $ticket->convertToJson();
        });

        return compact('tickets', 'scope', 'scopes', 'criterions');
    }

    function filterTickets()
    {
//        \request('criterions')
        if (\request('criterions')) {
            \Session::put('ticket.filter', \request('criterions'));
        }


        return $this->index();
    }

    public function handleTicketsScope()
    {

        if (\request('scope') != '') {
            \Session::put('ticket.scope', \request()->get('scope'));;
        }


        if (\request('criterions')) {
            \Session::put('ticket.filter', \request('criterions'));
        }

        $scope = \Session::get('ticket.scope', 'my_pending');

        if (\Session::has('ticket.filter')) {
            $query = Ticket::scopedView('in_my_groups')->filter(session('ticket.filter'));
        } else {
            $query = Ticket::scopedView($scope);
        }

        $filters = \Session::get('ticket.filter', null);
        $scopes = TicketViewScope::getScopes();

        return collect(['scope' => $scope, 'query' => $query, 'scopes' => $scopes, 'criterions' => $filters]);
    }

    function store(Request $request)
    {
        /** @var UploadedFile $file */

        $requestedTicket = $request->get('ticket');

        $items = json_decode($request->input('ticket.fields'), true);

        $validation = [];

        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $field = CustomField::find($key);
                if ($field && $field->required && $item == '') {
                    $validation[$key] = "This field is required";
                }
            }

            if (count($validation)) {
                return response()->json(['errors' => $validation, 'error_code' => 400]);
            }
        }


        $totalFileSizes = 0.0;

        foreach ($request->files as $files) {
            foreach ($files as $file) {
                $totalFileSizes += number_format($file->getSize() / 1048576, 3);
            }
        }

        if ($totalFileSizes > 10) {
            return response()->json(['file_size_error' => ['Attachments size should not exceed 10MB'], 'error_code' => 402]);
        }

        $validator = \Validator::make($request->all(), ['ticket.subject' => 'required', 'ticket.priority_id' => 'required'],[
            'ticket.priority_id.required'=> 'Priority field is required.',
            'ticket.subject.required'=> 'Subject field is required.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'error_code' => 400]);
        }

        $ticket = Ticket::create([
            'subject' => $requestedTicket['subject'],
            'description' => $requestedTicket['description'] ?? '',
            'requester_id' => $requestedTicket['requester_id'],
            'creator_id' => \Auth::id(),
            'status_id' => 1,
            'category_id' => $requestedTicket['category_id'],
            'subcategory_id' => $requestedTicket['subcategory_id'],
            'item_id' => $requestedTicket['item_id'],
            'priority_id' => $requestedTicket['priority_id'],

        ]);

        if ($items && count($items)){
            foreach ($items as $key => $item) {
                if ($item) {
                    $field = CustomField::find($key)->name ?? '';

                    $ticket->fields()->create(['name' => $field, 'value' => $item]);
                }
            }
        }



        return response($ticket->id);
//        return $ticket;
    }
}