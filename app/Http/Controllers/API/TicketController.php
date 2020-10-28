<?php


namespace App\Http\Controllers\API;


use App\Helpers\Ticket\TicketViewScope;
use App\Http\Requests\Request;
use App\Ticket;
use Illuminate\Support\Collection;

class TicketController
{
    function index()
    {
        if (\request('scope') != '') {
            \Session::put('ticket.scope', \request()->get('scope'));
        }

        $ticketScope = $this->handleTicketsScope();
        $query = $ticketScope['query'];
        $scope = $ticketScope['scope'];
        $scopes = $ticketScope['scopes'];

        $tickets = $query->latest('id')->paginate();

        $tickets->getCollection()->transform(function (Ticket $ticket) {
            return $ticket->convertToJson();
        });
        return compact('tickets', 'scope', 'scopes');
    }


    public function handleTicketsScope()
    {

        if (\request('scope') != '') {
            \Session::put('ticket.scope', \request()->get('scope'));;
        }

        $scope = \Session::get('ticket.scope', 'my_pending');

        if (\Session::has('ticket.filter')) {
            $query = Ticket::scopedView('in_my_groups')->filter(session('ticket.filter'));
        } else {
            $query = Ticket::scopedView($scope);
        }

        $scopes = TicketViewScope::getScopes();

        return collect(['scope' => $scope, 'query' => $query, 'scopes' => $scopes]);
    }
}