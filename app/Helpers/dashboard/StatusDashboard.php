<?php


namespace App\Helpers\dashboard;


use App\Ticket;
use Carbon\Carbon;

class StatusDashboard
{
    private $rowData;
    private $to;
    private $from;

    public function __construct($filters = [])
    {
        $this->to = Carbon::now()->format('Y-m-d h:m:s');
        $this->from = Carbon::now()->subYear()->format('Y-m-d h:m:s');

        $this->rowData = Ticket::query();
        $this->join();
        $this->condition();
        $this->select();
        $this->rowData = $this->rowData->get();

        $this->getTicketCreatedVsClosed();
    }

    function getTicketCreatedVsClosed()
    {

        $createdTickets = $this->rowData->whereBetween('created_at', [$this->from, $this->to])->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('Y-m');
        })->map(function ($item, $key) {
            return $item->count();
        });

        $closedTickets = $this->rowData->whereBetween('close_date', [$this->from, $this->to])->groupBy(function ($ticket) {
            return Carbon::parse($ticket->close_date)->format('Y-m');
        })->map(function ($item, $key) {
            return $item->count();
        });

        $finalData = collect();
        $createdTickets->each(function ($value, $key) use ($finalData, $closedTickets) {
            $finalData->put($key, ['created' => $value, 'closed' => $closedTickets->get($key) ?? 0]);
        });

        $finalData->put('total', ['created' => $finalData->sum('created'),
            'closed' => $finalData->sum('closed')]);
        dd($finalData);
//        $finalData =


    }

    private function join()
    {
        $this->rowData->leftJoin('categories as c', 'c.id', '=', 'tickets.category_id')
            ->leftJoin('priorities as p', 'p.id', '=', 'tickets.priority_id')
            ->leftJoin('statuses as st', 'st.id', '=', 'tickets.status_id')
            ->leftJoin('users as req', 'req.id', '=', 'tickets.requester_id')
            ->leftJoin('users as tech', 'tech.id', '=', 'tickets.technician_id');
    }

    private function condition()
    {
        $this->rowData->where(function ($q) {
            $q->whereBetween('tickets.created_at', [$this->from, $this->to])
                ->orWhereBetween('tickets.close_date', [$this->from, $this->to]);
        })->where('c.business_unit_id', 2);
    }

    private function select()
    {
        $this->rowData
            ->select([
                'tickets.id',
                'tickets.created_at',
                'tickets.close_date',
                'st.name as Status',
                'c.name as Category',
                'req.name as Requester',
            ]);
    }

}