<?php


namespace App\Helpers\dashboard;


use App\Survey;
use App\Ticket;
use App\UserSurvey;
use App\UserSurveyAnswer;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardInfo
{
    protected $filters;

    public $ticketOverView = [];
    public $ticketsByCategory = [];
    public $ticketsByStatus = [];
    public $ticketsByCoordinator = [];
    public $customerSatisfaction = [];
    public $business_unit;

    public function __construct($filters, $businessUnit)
    {

        $this->filters = $filters;
        $this->business_unit = $businessUnit;

        $this->process();
    }

    function process()
    {
        $this->ticketOverViewProcess();
        $this->ticketsByCategory();
        $this->ticketsByStatus();
        $this->ticketsByCoordinator();
        $this->customerSatisfactions();
    }


    private function ticketOverViewProcess()
    {
        $from = Carbon::now()->subMonth()->firstOfMonth();

        $to = Carbon::now()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $query = $this->filterByDate($from, $to);
//        dd($from,$to);
        $this->getTicketOverViewData($query);
    }

    private function ticketsByCategory()
    {
        $from = Carbon::now()->firstOfMonth();

        $to = Carbon::now()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $query = $this->filterByDate($from, $to);

        $total_tickets = $query->count();

        $this->ticketsByCategory = $query->leftJoin('categories as ca', 'tickets.category_id', '=', 'ca.id')
            ->groupBy(['ca.name'])
            ->select(\DB::raw('ca.name , count(tickets.id) as count, (count(tickets.id) / ' . $total_tickets . ' * 100) as percentage'))
            ->where('ca.business_unit_id', $this->business_unit->id)->get()->each(function ($item) {
                $item['percentage'] = number_format($item['percentage'], 2);
            })->pluck('count', 'name')->toArray();

    }


    function filterByDate($from, $to)
    {
        if (isset($this->filters['from']) && $this->filters['from']) {
            $from = Carbon::parse($this->filters['from']);
        }

        if (isset($this->filters['to']) && $this->filters['to']) {
            $to = Carbon::parse($this->filters['to'])->addHours(11)->addMinutes(59)->addSeconds(59);
        }

        return Ticket::leftJoin('categories as cat', 'tickets.category_id', '=', 'cat.id')
            ->leftJoin('statuses as st', 'tickets.status_id', '=', 'st.id')
            ->leftJoin('users as tech', 'tickets.technician_id', '=', 'tech.id')
            ->where('tickets.created_at', '>=', $from->toDateTimeString())
            ->where('tickets.created_at', '<=', $to->toDateTimeString())
            ->where('cat.business_unit_id', $this->business_unit->id);
    }

    /**
     * @param $query \Illuminate\Database\Query\Builder
     * @param $from
     */
    function getTicketOverViewData($query)
    {

        $groupBy = 'monthname(tickets.created_at) as month';

        if (isset($this->filters['from']) && $this->filters['from']) {
            $groupBy = "(select '{$this->filters['from']} - {$this->filters['to']}') as month";
        }

        $this->ticketOverView = $query->select('st.name', \DB::raw("tickets.id , tickets.status_id , tickets.overdue"), \DB::raw($groupBy))
            ->get()->groupBy('month')->map(function ($tickets, $key) {
                return [
                    'all' => $tickets->count(),
                    'overdue' => $tickets->where('overdue', 1)->count(),
                    'open' => $tickets->whereIn('status_id', [1, 2, 3])->count(),
                    'resolved' => $tickets->whereIn('status_id', [7, 9, 10])->count(),
                    'onHold' => $tickets->whereIn('status_id', [4, 5, 6])->count(),
                    'closedOnTime' => $tickets->whereIn('status_id', [8])->where('overdue', 0)->count(),
                ];
            })->reverse();

    }

    private function ticketsByStatus()
    {
        $query = $this->filterByDate(Carbon::now()->submonth()->firstOfMonth(), Carbon::now()->lastOfMonth());

        $groupBy = 'monthname(tickets.created_at) as month';

        if (isset($this->filters['from']) && $this->filters['from']) {
            $groupBy = "(select '{$this->filters['from']} - {$this->filters['to']}') as month";
        }

        $query
            ->select(\DB::raw('st.name as name, count(tickets.id) as count'), \DB::raw($groupBy))
            ->orderBy('name')
            ->groupBy(['name', 'month'])->get()->groupBy('month')->map(function ($item, $key) {
                $total = $item->pluck('count', 'name')->values()->sum();

                $this->ticketsByStatus[$key]['labels'][] = $item->pluck('count', 'name')->keys();
                $this->ticketsByStatus[$key]['values'][] = $item->pluck('count', 'name')->values();
            });

//        dd($this->ticketsByStatus);
    }

    private function ticketsByCoordinator()
    {
        $query = $this->filterByDate(Carbon::now()->firstOfMonth(), Carbon::now()->lastOfMonth());

        $query->each(function ($ticket) {
            if (!isset($this->ticketsByCoordinator[$ticket->technician->name])) {
                $this->ticketsByCoordinator[$ticket->technician->name] = ['total' => 0, 'resolved' => 0, 'resolvedOnTime' => 0];
            }
            $this->ticketsByCoordinator[$ticket->technician->name]['total'] += 1;
            $this->ticketsByCoordinator[$ticket->technician->name]['resolved'] += in_array($ticket->status_id, [7]) ? 1 : 0;
            $this->ticketsByCoordinator[$ticket->technician->name]['resolvedOnTime'] += (in_array($ticket->status_id, [7]) && !$ticket->overdue) ? 1 : 0;
        });
    }

    private function customerSatisfactions()
    {
        $questions = collect();

        $from = Carbon::now()->firstOfMonth()->toDateTimeString();
        $to = Carbon::now()->lastOfMonth()->toDateTimeString();

        if (isset($this->filters['from']) && $this->filters['from']) {
            $from = Carbon::parse($this->filters['from']);
        }

        if (isset($this->filters['to']) && $this->filters['to']) {
            $to = Carbon::parse($this->filters['to'])->addHours(11)->addMinutes(59)->addSeconds(59);
        }

        UserSurveyAnswer::with('survey', 'survey.survey.categories', 'answer', 'answer.question')
            ->whereHas('survey.survey.categories', function ($q) {
                $q->where('business_unit_id', $this->business_unit->id);
            })
            ->whereHas('survey', function ($q) use ($from, $to) {
                $q->where('is_submitted', 1)->where('created_at', '>=', $from)
                    ->where('created_at', '<=', $to);
            })->each(function ($userAnswer) use ($questions) {
                $questions->push(collect(['question' => $userAnswer->answer->question->description, 'answer' => $userAnswer->answer->description]));
            });


        $this->customerSatisfaction = $questions->groupBy('question')->map(function ($item) {
            return collect($item)->groupBy('answer')->map(function ($answer) {
                return count($answer);
            });
        });
    }
}