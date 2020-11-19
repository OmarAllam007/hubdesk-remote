<?php


namespace App\Http\Controllers\API;


use App\Helpers\Ticket\TicketViewScope;
use App\Http\Requests\Request;
use App\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TicketController
{
    function index()
    {
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
}