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
    public $ticketsBySubcategory = [];
    public $servicePerformance = [];
    public $ticketsByStatus = [];
    public $ticketsByCoordinator = [];
    public $customerSatisfaction = [];
    public $topServices;
    public $yearlyPerformance = [];
    public $customerSatisfactionOverYear = [];

    public $business_unit;
    private $_submittedTickets;
    private $_totalScore;

    public function __construct($filters, $businessUnit)
    {

        $this->filters = $filters;
        $this->business_unit = $businessUnit;

        $this->process();
    }

    function process()
    {
        $this->ticketOverViewProcess();
        $this->getTopServices();
        $this->getPerformanceOverYear();
        $this->getSatisfactionOverYear();
        $this->ticketsByCategory();
        $this->ticketsBySubcategory();
        $this->ticketsByCategoryPerformance();
        $this->ticketsByStatus();
        $this->ticketsByCoordinator();
        $this->customerSatisfactions();
    }


    private function ticketOverViewProcess()
    {
        $from = Carbon::now()->subMonth()->firstOfMonth();

        $to = Carbon::now()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $query = $this->filterByDate($from, $to);

        $this->getTicketOverViewData($query);
    }

    private function ticketsByCategory()
    {
        $from = Carbon::now()->firstOfMonth();

        $to = Carbon::now()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $query = $this->filterByDate($from, $to);

        $total_tickets = $query->count();

        $this->ticketsByCategory = $query
            ->groupBy(['ca.name'])
            ->select(\DB::raw('ca.name , count(tickets.id) as count, (count(tickets.id) / ' . $total_tickets . ' * 100) as percentage'))
            ->where('ca.business_unit_id', $this->business_unit->id)->get()->each(function ($item) {
                $item['percentage'] = number_format($item['percentage'], 2);
            })->pluck('count', 'name')->toArray();

    }

    function ticketsBySubcategory()
    {
        $from = Carbon::now()->firstOfMonth();

        $to = Carbon::now()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $query = $this->filterByDate($from, $to);

        $total_tickets = $query->count();

        $this->ticketsBySubcategory = $query
            ->groupBy(['sub.name'])
            ->select(\DB::raw('sub.name ,count(tickets.id) as count, (count(tickets.id) / ' . $total_tickets . ' * 100)  percentage'))
            ->where('ca.business_unit_id', $this->business_unit->id)->get()->each(function ($item) {
                $item['percentage'] = number_format($item['percentage'], 2);
            })->map(function ($item, $key) {

                return ['percentage' => $item->count, 'name' => $item->name . '( ' . $item->percentage . '% ) '];
            })->pluck('percentage', 'name')->toArray();
//dd($this->ticketsBySubcategory);


    }


    function filterByDate($from, $to)
    {
        if (isset($this->filters['from']) && $this->filters['from']) {
            $from = Carbon::parse($this->filters['from']);
        }

        if (isset($this->filters['to']) && $this->filters['to']) {
            $to = Carbon::parse($this->filters['to'])->addHours(11)->addMinutes(59)->addSeconds(59);
        }

        return Ticket::with('user_survey', 'category')
            ->leftJoin('categories as ca', 'tickets.category_id', '=', 'ca.id')
            ->leftJoin('subcategories as sub', 'tickets.subcategory_id', '=', 'sub.id')
            ->leftJoin('statuses as st', 'tickets.status_id', '=', 'st.id')
            ->leftJoin('users as tech', 'tickets.technician_id', '=', 'tech.id')
            ->where('tickets.created_at', '>=', $from->toDateTimeString())
            ->where('tickets.created_at', '<=', $to->toDateTimeString())
            ->where('ca.business_unit_id', $this->business_unit->id);
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

                if (isset($this->filters['from']) && $this->filters['from']) {
                    $from = Carbon::parse($this->filters['from']);
                } else {
                    $from = new Carbon('first day of ' . $key);
                }

                if (isset($this->filters['to']) && $this->filters['to']) {
                    $to = Carbon::parse($this->filters['to'])->addHours(11)->addMinutes(59)->addSeconds(59);
                } else {
                    $to = new Carbon('last day of ' . $key);
                }

                $surveys = UserSurvey::where('is_submitted', 1)
                    ->whereHas('survey.categories', function ($q) {
                        $q->where('business_unit_id', $this->business_unit->id);
                    })->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();

                $surveys_count = $surveys->count();
                $surveys_points = $surveys->sum(function ($survey) {
                    return $survey->total_score;
                });

                return [
                    'all' => $tickets->count(),
                    'overdue' => $tickets->where('overdue', 1)->count(),
                    'open' => $tickets->whereIn('status_id', [1, 2, 3])->count(),
                    'resolved' => $tickets->whereIn('status_id', [7, 8, 9, 10])->count(),
                    'onHold' => $tickets->whereIn('status_id', [4, 5, 6])->count(),
                    'closedOnTime' => $tickets->whereIn('status_id', [9, 10, 8])->where('overdue', 0)->count(),
                    'customer_satisfaction' => $surveys_count ? number_format($surveys_points / $surveys_count, 1) : 0,
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

    }

    private function ticketsByCoordinator()
    {
        $query = $this->filterByDate(Carbon::now()->firstOfMonth(), Carbon::now()->lastOfMonth());

        $query->each(function ($ticket) {
            if (!$ticket->technician) {
                return;
            }

            if (!isset($this->ticketsByCoordinator[$ticket->technician->name])) {
                $this->ticketsByCoordinator[$ticket->technician->name] = ['total' => 0, 'resolved' => 0, 'resolvedOnTime' => 0];
            }
            $this->ticketsByCoordinator[$ticket->technician->name]['total'] += 1;
            $this->ticketsByCoordinator[$ticket->technician->name]['resolved'] += in_array($ticket->status_id, [7, 8, 9]) ? 1 : 0;
            $this->ticketsByCoordinator[$ticket->technician->name]['resolvedOnTime'] += (in_array($ticket->status_id, [7, 8, 9]) && !$ticket->overdue) ? 1 : 0;


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

        $query = UserSurvey::
        whereHas('survey.categories', function ($q) {
            $q->where('business_unit_id', $this->business_unit->id);
        })
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to);

        $this->customerSatisfaction['total_responses'] = $query->count();
        $this->customerSatisfaction['total_submitted'] = $query->where('is_submitted', 1)->count();

        $total_responses_percentage = $query->get()->sum(function ($survey) {
            return $survey->total_score;
        });

        $this->customerSatisfaction['total_responses_percentage'] = $this->customerSatisfaction['total_submitted'] ?
            number_format($total_responses_percentage / $this->customerSatisfaction['total_submitted'], 1) : 0;

        $answers = UserSurveyAnswer::with('survey', 'survey.survey.categories', 'answer', 'answer.question')
            ->whereHas('survey.survey.categories', function ($q) {
                $q->where('business_unit_id', $this->business_unit->id);
            })
            ->whereHas('survey', function ($q) use ($from, $to) {
                $q->where('is_submitted', 1)->where('created_at', '>=', $from)
                    ->where('created_at', '<=', $to);
            });

        $answers->each(function ($userAnswer) use ($questions) {
            $questions->push(collect(['question' => $userAnswer->answer->question->description, 'answer' => $userAnswer->answer->description, 'degree' => $userAnswer->answer->degree]));
        });

        $questions->groupBy('question')->map(function ($item, $key) {
            $this->customerSatisfaction['questions'][$key]['percentage'] = number_format($item->sum('degree') / $item->count(), 1);
            $this->customerSatisfaction['questions'][$key]['answers'] = collect($item)->groupBy('answer')->map(function ($answers) use ($item) {
                return count($answers);
            });
        });
    }

    private function ticketsByCategoryPerformance()
    {
        $query = $this->filterByDate(Carbon::now()->firstOfMonth(), Carbon::now()->lastOfMonth());

        $query->each(function ($ticket) use ($query) {
            if (!$ticket->category) {
                return;
            }

            if (!isset($this->servicePerformance[$ticket->category->name])) {
                $this->servicePerformance[$ticket->category->name] = ['total' => 0, 'resolved' => 0, 'resolvedOnTime' => 0];
            }
            $this->servicePerformance[$ticket->category->name]['total'] += 1;
            $this->servicePerformance[$ticket->category->name]['resolved'] += in_array($ticket->status_id, [7, 8, 9]) ? 1 : 0;
            $this->servicePerformance[$ticket->category->name]['resolvedOnTime'] += (in_array($ticket->status_id, [7, 8, 9]) && !$ticket->overdue) ? 1 : 0;
        });


    }

    private function getTopServices()
    {
        $from = Carbon::now()->subMonth()->firstOfMonth();
        $to = Carbon::now()->subMonth()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $ticketsQuery = Ticket::with('category', 'subcategory')
            ->leftJoin('categories as ca', 'tickets.category_id', '=', 'ca.id')
            ->where('tickets.created_at', '>=', $from)
            ->where('tickets.created_at', '<=', $to)
            ->where('ca.business_unit_id', $this->business_unit->id)->get();

        $ticketsQuery = $this->business_unit->id != 11 ? $ticketsQuery->groupBy('category.name') : $ticketsQuery->groupBy('subcategory.name');

        $tickets = $ticketsQuery->sortByDesc(function ($data) {
            return $data->count();
        })->map(function ($tickets, $key) {
            return $tickets->count();
        });

        $this->topServices = $tickets->slice(0, 5);
        $others = $tickets->slice(5)->sum();

        $this->topServices = $this->topServices->put('Others', $others)->toArray();

    }

    private function getPerformanceOverYear()
    {
        $from = Carbon::now()->firstOfYear();
        $to = Carbon::now()->subMonth()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        $onTimeTickets = Ticket::selectRaw("count(*) total , monthname(tickets.created_at) month")
            ->leftJoin('categories as ca', 'tickets.category_id', '=', 'ca.id')
            ->where('tickets.created_at', '>=', $from)
            ->where('tickets.created_at', '<=', $to)
            ->whereIn('status_id', [7, 8, 9, 10])
            ->where('overdue', 0)
            ->where('ca.business_unit_id', $this->business_unit->id)
            ->groupBy('month')->get()->keyBy('month')->map(function ($item) {
                return $item->total;
            });

        $this->yearlyPerformance = Ticket::selectRaw("count(*) total , monthname(tickets.created_at) month")
            ->leftJoin('categories as ca', 'tickets.category_id', '=', 'ca.id')
            ->where('tickets.created_at', '>=', $from)
            ->where('tickets.created_at', '<=', $to)
            ->where('ca.business_unit_id', $this->business_unit->id)
            ->groupBy('month')->get()->keyBy('month')->map(function ($item) use ($onTimeTickets) {
                return ['total' => $item->total, 'ontime' => $onTimeTickets->get($item->month), 'month' => $item->month];
            })->toArray();
//        dd($this->yearlyPerformance);
    }

    private function getSatisfactionOverYear()
    {
        $from = Carbon::now()->firstOfYear();
        $to = Carbon::now()->subMonth()->lastOfMonth()->addHours(11)->addMinutes(59)->addSeconds(59);

        /** @var Collection $months */
        $months = UserSurvey::where('is_submitted', 1)
            ->whereHas('survey.categories', function ($q) {
                $q->where('business_unit_id', $this->business_unit->id);
            })->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->get()->groupBy(function ($item) {
                return $item->created_at->englishMonth;
            });

        $this->customerSatisfactionOverYear = $months->map(function ($surveys) {
            $surveys_count = $surveys->count();
            $surveys_points = $surveys->sum(function ($survey) {
                return $survey->total_score;
            });

            return number_format($surveys_points / $surveys_count, 1);
        });

    }
}