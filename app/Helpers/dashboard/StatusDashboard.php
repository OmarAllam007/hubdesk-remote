<?php


namespace App\Helpers\dashboard;


use App\Category;
use App\Dashboard\Status\TicketPriorityVsCategory;
use App\Dashboard\Status\TicketsPriority;
use App\Dashboard\Status\TicketsStatusVsCategory;
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
    public $ticketsStatusVsCategory;
    public $ticketsPriorityVsCategory;
    public $closedTicketsStatusVsCategory;
    public $closedTicketsPriorityVsCategory;

    public function __construct($filters = [])
    {
        $this->prepareQuery();

        $this->getTicketCreatedVsClosed();
        $this->ticketsPriority();
        $this->ticketsStatusVsCategory();
        $this->ticketsPriorityVsCategory();
        $this->closedTicketsStatusVsCategory();
        $this->closedTicketsPriorityVsCategory();

//        dd($this->ticketsPriority);

    }

    private function prepareQuery()
    {
        $this->categories = Category::orderBy('name')->where('business_unit_id', 2)
            ->get()->pluck('name')->unique();

        $this->to = Carbon::now()->format('Y-m-d h:m:s');
        $this->from = Carbon::now()->subYear()->format('Y-m-d h:m:s');


        $this->rowData = Ticket::query();
        $this->join();
        $this->condition();
        $this->select();
        $this->rowData = $this->rowData->get();
    }

    function getTicketCreatedVsClosed()
    {

        $createdTickets = $this->rowData->whereBetween('created_at', [$this->from, $this->to])->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('m/Y');
        })->map(function ($item, $key) {
            return $item->count();
        });


        $closedTickets = $this->rowData->whereNotNull('close_date')
            ->whereBetween('close_date', [$this->from, $this->to])->whereIn('StatusID', [7, 8, 9])->groupBy(function ($ticket) {
                return Carbon::parse($ticket->close_date)->format('m/Y');
            })->map(function ($item, $key) {
                return $item->count();
            });

        $finalData = collect();

        $createdTickets->each(function ($value, $key) use ($finalData, $closedTickets) {
            $finalData->push(['created' => $value, 'closed' => $closedTickets->get($key) ?? 0, 'month' => $key]);
        });


        $this->ticketsCreatedClosed = [];
//        @todo sort array with a date :(
        $this->ticketsCreatedClosed['rows'] = $finalData->sortBy(function ($col) {
            $dateMonthArray = explode('/', $col['month']);
            $month = $dateMonthArray[0];
            $year = $dateMonthArray[1];

            return Carbon::createFromDate($year, $month, 1);
        }, SORT_REGULAR, true)->values()->all();

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
        })->where('c.business_unit_id', 2)
            ->where('req.business_unit_id', 2);
    }

    private function select()
    {
        $this->rowData
            ->select([
                'tickets.id',
                'tickets.created_at',
                'tickets.close_date',
                'st.name as Status',
                'st.id as StatusID',
                'c.name as Category',
                'req.name as Requester',
                'p.name as Priority',
            ]);
    }

    private function ticketsPriority()
    {
        $ticketsStatusVsCategory = new TicketsPriority($this->rowData, $this->from, $this->to, $this->categories);
        $this->ticketsPriority = $ticketsStatusVsCategory->process();
    }


    private function ticketsStatusVsCategory()
    {
        $ticketsStatusVsCategory = new TicketsStatusVsCategory($this->rowData, $this->from, $this->to, $this->categories, 'created_at', [1, 2, 3, 4, 5, 6]);
        $this->ticketsStatusVsCategory = $ticketsStatusVsCategory->process();
    }

    private function ticketsPriorityVsCategory()
    {
        $ticketsPriorityVsCategory = new TicketPriorityVsCategory($this->rowData, $this->from, $this->to, $this->categories,
            'created_at', [1, 2, 3, 4, 5, 6]);
        $this->ticketsPriorityVsCategory = $ticketsPriorityVsCategory->process();
    }

    private function closedTicketsStatusVsCategory()
    {
        $closedTicketsStatusVsCategory = new TicketsStatusVsCategory($this->rowData, $this->from, $this->to, $this->categories,
            'close_date', [7, 8, 9]);
        $this->closedTicketsStatusVsCategory = $closedTicketsStatusVsCategory->process();
    }

    private function closedTicketsPriorityVsCategory()
    {
        $closedTicketsPriorityVsCategory = new TicketPriorityVsCategory($this->rowData, $this->from, $this->to, $this->categories,
            'close_date', [7, 8, 9]);
        $this->closedTicketsPriorityVsCategory = $closedTicketsPriorityVsCategory->process();
    }


}