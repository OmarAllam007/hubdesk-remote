<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-08-25
 * Time: 11:30
 */

namespace App\Reports;

use App\Helpers\ChromePrint;
use App\Status;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Workbook;

class MonthlyKPIWithApprovals extends ReportContract
{
    /** @var Builder */
    protected $query;

    /** @var int */
    protected $row = 1;

    /** @var string */

    protected $max_approvals = 0;

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
        $data = $this->query->get();
        $max = 0;

        $this->max_approvals = $data->max(function ($item) use ($max) {
            if ($item->approvals->count() > $max) {
                $max = $item->approvals->count();
            }
            return $max;
        });

        return \Excel::create(str_slug($this->report->title), function ($excel) use ($data) {
            $excel->sheet('Monthly KPI With Approvals', function ($sheet) use ($data) {
                $approvals_header = collect();

                for ($i = 0; $i<$this->max_approvals;$i++){
                    $approvals_header->push(["Approval Level ".($i+1)." Sent At","Action Date","Status"]);
                }

                $sheet->row($this->row, array_merge([
                    'ID', 'Subject', 'Requester',
                    'Technician', 'Category', 'Status', 'Created Date', 'Date of Departure', 'Days between departure and create', 'Due Date',
                    'Resolved Date', 'Business Unit'
                ],$approvals_header->flatten()->toArray()));


                $data->each(function ($ticket) use ($sheet) {
                    /** @var Worksheet $sheet */
                    $row_data = [
                        $ticket->id, $ticket->subject, $ticket->requester, $ticket->technician,
                        $ticket->category, $ticket->status, $ticket->created_at, $ticket->date_of_dept, $ticket->difference ?? 'Not Assigned',
                        $ticket->due_date, $ticket->resolve_date, $ticket->business_unit
                    ];

                    $ticket_approvals = $ticket->approvals;
                    $approvals = collect();
                    if ($ticket_approvals->count()) {
                        foreach ($ticket_approvals as $approval){
                            $approvals->push([$approval->created_at,
                                 $approval->approval_date
                                ,$approval->status_str]);
                        }
                    }

                    $sheet->row(++$this->row, array_merge($row_data,$approvals->flatten()->toArray()));
                });



                $sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });

        });
    }

    function pdf()
    {
        return;

        $data = $this->query->get();
        $approvals_header = collect();
        $max = 0;

        $approvals_count = $data->max(function ($item) use ($max) {
            if ($item->approvals->count() > $max) {
                $max = $item->approvals->count();
            }
            return $max;
        });

        for ($i = 0; $i<$this->max_approvals;$i++){
            $approvals_header->push(["Approval Level ".($i+1)." Sent At","Action Date","Status"]);
        }

        $columns = array_merge([
            'ID', 'Subject', 'Requester',
            'Technician', 'Category', 'Status', 'Created Date', 'Date of Departure', 'Difference', 'Due Date',
            'Resolved Date', 'Business Unit'
        ],$approvals_header->flatten()->toArray());


        $max = 0;
        $this->max_approvals = $data->max(function ($item) use ($max) {
            if ($item->approvals->count() > $max) {
                $max = $item->approvals->count();
            }
            return $max;
        });

        $content = view('emails.report.monthly_kpi_report_pdf', ['data' => $data, 'report' => $this->report, 'columns'=>$columns,'approvals_count'=>$approvals_count]);

        $filepath = storage_path('app/' . uniqid('report') . '.html');
        file_put_contents($filepath, $content->render());

        $print = new ChromePrint($filepath);
        $file = $print->print();

        return $file;
    }

    function csv()
    {

    }

    private function applyFilters()
    {
        if(!$this->parameters['start_date']){
            $start_date = new Carbon('first day of last month');
        }else{
            $start_date = Carbon::parse($this->parameters['start_date']);
        }

        $start_date->setTime(0, 0, 0);
        $this->query->where('t.created_at', '>=', $start_date);


        if (!empty($this->parameters['end_date'])) {
            $end_date = Carbon::parse($this->parameters['end_date']);

        }else{
            $end_date = new Carbon('last day of last month');
        }
        $end_date->setTime(23, 59, 59);
        $this->query->where('t.created_at', '<=', $end_date);

        if (!empty($this->parameters['technician'])) {
            if (is_array($this->parameters['technician'])) {
                $technicians = $this->parameters['technician'];
            } else {
                $technicians = array_map('trim', explode(',', $this->parameters['technician']));
            }

            $this->query->whereIn('t.technician_id', $technicians);
        }

        if (!empty($this->parameters['category'])) {
            if (is_array($this->parameters['category'])) {
                $categories = $this->parameters['category'];
            } else {
                $categories = array_map('trim', explode(',', $this->parameters['category']));
            }

            $this->query->whereIn('t.category_id', $categories);
        }

        return $this->query;

    }
}