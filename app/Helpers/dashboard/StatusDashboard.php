<?php


namespace App\Helpers\dashboard;


use App\Category;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StatusDashboard
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private $rowData;
    private $to;
    private $from;
    private $categories;

    public $ticketsPriority;
    public $ticketsCreatedClosed;

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
        $this->ticketsPriority();
    }

    function getTicketCreatedVsClosed()
    {
        $createdTickets = $this->rowData->whereBetween('created_at', [$this->from, $this->to])->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('m-Y');
        })->map(function ($item, $key) {

            return $item->count();
        });


        $closedTickets = $this->rowData->whereBetween('close_date', [$this->from, $this->to])->groupBy(function ($ticket) {
            return Carbon::parse($ticket->close_date)->format('m-Y');
        })->map(function ($item, $key) {
            return $item->count();
        });
        $finalData = collect();

        $createdTickets->each(function ($value, $key) use ($finalData, $closedTickets) {
            $finalData->put($key, ['created' => $value, 'closed' => $closedTickets->get($key) ?? 0, 'month' => "" . $key]);
        })->sortBy('created');

//        $finalData->put('total', ['created' => $finalData->sum('created'),
//            'closed' => $finalData->sum('closed')]);

        $this->ticketsCreatedClosed = [];
        $this->ticketsCreatedClosed['rows'] = $finalData->toArray();
        $this->ticketsCreatedClosed['total'] = ['created' => $finalData->sum('created'),
            'closed' => $finalData->sum('closed')];
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
                'p.name as Priority',
            ]);
    }

    private function ticketsPriority()
    {

        $data = $this->ticketsPriorityFillData();

        $footerRow = $this->ticketsPriorityFillFooter($data)['footerRow'];
        $chartData = $this->ticketsPriorityFillFooter($data)['chartData'];

        $footerRow->put('Total', $footerRow->values()->sum());

        $ticketPriorityData = collect();
        $ticketPriorityData->put('priorities', $data);
        $ticketPriorityData->put('chartData', $chartData);
        $ticketPriorityData->put('header', $this->categories);
        $ticketPriorityData->put('footer', $footerRow);

        $this->ticketsPriority = $ticketPriorityData;
    }

    private function ticketsPriorityFillData()
    {
        $this->categories = Category::orderBy('name')->where('business_unit_id', 2)
            ->get()->pluck('name')->unique();

        return $this->rowData->whereBetween('created_at', [$this->from, $this->to])
            ->groupBy(function ($ticket) {
                return $ticket->Priority ? $ticket->Priority : 'Not Assigned';
            })->sortKeys()->map(function (Collection $items, $key) {
                $itemsArr = collect();

                foreach ($this->categories as $category) {
                    $itemsArr->put($category, 0);
                }

                $items->each(function ($item) use ($itemsArr) {
                    $itemsArr->put($item->Category, $itemsArr->get($item->Category) + 1);
                });

                return [
                    'Total' => $items->count(),
                    'items' => $itemsArr
                ];
            });
    }

    private function ticketsPriorityFillFooter($data)
    {
        $footerRow = collect();
        $chartData = collect();
        foreach ($this->categories as $category) {
            $footerRow->put($category, 0);
        }

        $data->each(function ($priority, $key) use ($chartData, $footerRow) {
            $chartData->put($key, $priority['Total']);

            $priority['items']->each(function ($item, $itemKey) use ($footerRow) {
                $footerRow->put($itemKey, $footerRow->get($itemKey) + $item);
            });

        });
        $data = collect(['footerRow' => $footerRow, 'chartData' => $chartData]);

        return $data;
    }

}