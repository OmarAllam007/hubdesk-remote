<?php


namespace App\Http\Controllers\API;


use App\Attachment;
use App\Category;
use App\CustomField;
use App\Helpers\Ticket\TicketViewScope;
use App\Jobs\NewTicketJob;
use App\Ticket;
use App\TicketRequirements;
use App\User;
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


        if (\request('selected_services')) {
            $servicesArr = \request('selected_services');
            $query->whereIn('category_id', $servicesArr);
        }

        $sortedServices = Category::with('business_unit')->where('is_disabled', 0)
            ->get()->map(function ($service) {
                return ['id' => $service->id, 'name' => $service->business_unit->name . ' > ' . $service->name];
            })->sortBy('name')->values()->all();

        $tickets = $query->latest('id')
            ->paginate();

        $tickets->getCollection()->transform(function (Ticket $ticket) {
            return $ticket->convertToJson();
        });

        return compact('tickets', 'scope', 'scopes', 'criterions', 'sortedServices');
    }

    function filterTickets()
    {
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
        $validation = $this->validateFields($items);

        if (count($validation)) {
            return response()->json(['errors' => $validation, 'error_code' => 400]);
        }

        if ($this->validateFiles($request) > 10) {
            return response()->json(['file_size_error' => ['Attachments size should not exceed 10MB'], 'error_code' => 402]);
        }

        $validator = \Validator::make($request->all(), ['ticket.subject' => 'required', 'ticket.priority_id' => 'required'], [
            'ticket.priority_id.required' => 'Priority field is required.',
            'ticket.subject.required' => 'Subject field is required.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'error_code' => 400]);
        }

        $ticket = $this->createTicket($request, $requestedTicket, $requestedTicket['requester_id']);




//        dispatch(new NewTicketJob($ticket));
        return response($ticket->id);
    }

    function validateFields($items)
    {
        $validation = [];

        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $field = CustomField::find($key);
                if ($field && $field->required && $item == '') {
                    $validation[$key] = "This field is required";
                }
            }
        }

        return $validation;
    }

    function validateFiles($request)
    {
        $totalFileSizes = 0.0;

        foreach ($request->files as $files) {
            foreach ($files as $file) {
                $totalFileSizes += number_format($file->getSize() / 1048576, 3);
            }
        }
        return $totalFileSizes;
    }

    function createTicket($request, $requestedTicket, $requester)
    {

        $ticketRequesterID = $requestedTicket['requester_id'] ?: $requestedTicket['creator_id'];
        $requesterUser = User::where('employee_id', $ticketRequesterID)->first();

        $clientInfo = ['client' => $request->userAgent(), 'ip_address' => $request->ip(), 'client_ip' => $request->getClientIp()];


        $requesterId = User::where('employee_id', $ticketRequesterID)->first() ?? auth()->user();

        $ticket = Ticket::create([
            'subject' => $requestedTicket['subject'],
            'description' => $requestedTicket['description'] ?? '',
            'requester_id' => $requesterId->id,
            'creator_id' => \Auth::id(),
            'status_id' => 1,
            'category_id' => $requestedTicket['category_id'],
            'subcategory_id' => $requestedTicket['subcategory_id'],
            'item_id' => $requestedTicket['item_id'],
            'priority_id' => $requestedTicket['priority_id'],
            'business_unit_id' => $requesterUser->business_unit_id ?? 34, //not assigned
            'client_info' => $clientInfo,
        ]);

        return $ticket;
    }

    /**
     * @param $items
     * @param $ticket
     * @return void
     */


//    TODO: THIS IS ONLY FOR KGS SERVICES 🤦🏻🤦🏻🤦🏻‍
    function uploadRequirements($ticket_id, $requirement_id)
    {

        $file = \request()->file('attachments')[0];
        $name = $file->getClientOriginalName();
        $folder = storage_path("app/public/kgs/ticket/" . $ticket_id . '/' . $requirement_id . '/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }
        $path = $folder . $name;

        if (is_file($path)) {
            $filename = uniqid() . '_' . $name;
            $path = $folder . $filename;
        }

        $file->move($folder, $name);

        $pathName =  "/kgs/ticket/" . $ticket_id . '/' . $requirement_id . '/'. $name;

        $ticketRequirements =  TicketRequirements::where('ticket_id', $ticket_id)
            ->where('id', $requirement_id)->first();

        $ticketRequirements->update([
           'path'=> $pathName,
        ]);

    }

    function downloadRequirements($ticket_id, $requirement_id)
    {
        $path = TicketRequirements::where('ticket_id',$ticket_id)->where('id',$requirement_id)
            ->first()->path;

        $file = public_path('storage') . $path;
        return response()->download($file);
    }


}