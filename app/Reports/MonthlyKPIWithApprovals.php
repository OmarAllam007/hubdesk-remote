<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-08-25
 * Time: 11:30
 */

namespace App\Reports;

use App\Status;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class MonthlyKPIWithApprovals extends ReportContract
{
    /** @var Builder */
    protected $query;

    /** @var int */
    protected $row = 1;

    /** @var string */
    protected $view = 'reports.monthly_kpi_with_approvals';

    function run()
    {
        $this->query = Ticket::withoutGlobalScopes()->with('sla', 'approvals')->from('tickets as t')
            ->join('users as tech', 't.technician_id', '=', 'tech.id')
            ->join('users as req', 't.requester_id', '=', 'req.id')
            ->join('statuses as st', 't.status_id', '=', 'st.id')
            ->join('categories as cat', 't.category_id', '=', 'cat.id')
            ->leftJoin('subcategories as subcat', 't.subcategory_id', '=', 'subcat.id')
            ->leftJoin('items as item', 't.item_id', '=', 'item.id')
            ->join('slas as sla', 't.sla_id', '=', 'sla.id')
            ->join('business_units as bu', 'req.business_unit_id', '=', 'bu.id');

        $this->fields();
        $this->applyFilters();
    }

    protected function fields()
    {
        $this->query->selectRaw('t.id as id, t.subject, t.created_at, t.due_date, t.resolve_date, 
        tech.name as technician, req.name as requester')
            ->selectRaw('t.sla_id, resolve_date, overdue, time_spent')
            ->selectRaw('cat.name as category, subcat.name as subcategory, item.name as item')
            ->selectRaw('st.name as status')
            ->selectRaw('bu.name as business_unit')
            ->selectRaw("@departure_date := (select `getDepartureDate`(t.id))  'date_of_dept',
          DATEDIFF(@departure_date, DATE_FORMAT(t.created_at, '%Y-%m-%d')) 'difference'");

    }

    function html()
    {
        $this->run();
        $page = LengthAwarePaginator::resolveCurrentPage();

        $count = $this->query->count();
        $items = $this->query->forPage($page, 20)->get();

        $max = 0;

        $approvals_count = $items->max(function ($item) use ($max) {
            if ($item->approvals->count() > $max) {
                $max = $item->approvals->count();
            }
            return $max;
        });

        $data = new LengthAwarePaginator($items, $count, $this->perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);


        return view('reports.monthly_kpi_with_approvals', ['data' => $data, 'report' => $this->report, 'approvals_count' => $approvals_count]);
    }

    function excel()
    {

    }

    function pdf()
    {

    }

    function csv()
    {

    }

    private function applyFilters()
    {
        $this->query->whereDate('t.created_at', '>=', Carbon::now()->month(8)->firstOfMonth()->toDateTimeString())
            ->whereDate('t.created_at', '<', Carbon::now()->month(8)->lastOfMonth()->toDateTimeString());
    }
}