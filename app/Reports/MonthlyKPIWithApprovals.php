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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Workbook;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $this->query = Ticket::withoutGlobalScopes()
            ->with('sla', 'approvals')
            ->from('tickets as t')
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
        tech.name as technician, req.name as requester, req.employee_id as employee_id')
            ->selectRaw('t.sla_id, resolve_date, overdue, time_spent')
            ->selectRaw('cat.name as category, subcat.name as subcategory, item.name as item')
            ->selectRaw('st.name as status')
            ->selectRaw('bu.name as business_unit')
            ->selectRaw(' @sla := format((sla.due_days) + (sla.due_hours / 8) + (sla.due_minutes / (8 * 60)),
                               1)                                                      \'SLA Delivery time\',

                @last_approval_date := (SELECT DATE_FORMAT(ta.approval_date, \'%Y-%m-%d %h:%m:%s\')
                                        from ticket_approvals ta
                                        where t.id = ta.ticket_id
                                        order by ta.created_at DESC
                                        limit 1),

                @last_updated_date := DATE_FORMAT((SELECT tr.created_at from ticket_replies tr where tr.ticket_id = t.id and tr.status_id IN (7,8,9) limit  1), \'%Y-%m-%d %h:%m:%s\'),

                @acceptance_date := (CASE
                                         WHEN @last_approval_date IS NULL
                                             THEN DATE_FORMAT(t.created_at, \'%Y-%m-%d %h:%m:%s\')
                                         ELSE @last_approval_date END)                 \'Acceptance Date\',

                @actual_delivery_time :=
                            (SELECT diffdate(@last_updated_date, @acceptance_date)) AS \'Actual delivery time\',

                @difference := @actual_delivery_time - @sla                            \'SLA Diff\',

                (CASE
                     WHEN (@difference <= 0)
                         THEN 100
                     WHEN (@difference >= 1 && @difference < 2)
                         THEN 75
                     WHEN (@difference >= 2 && @difference < 3)
                         THEN 70
                     WHEN (@difference >= 3 && @difference < 4)
                         THEN 60
                     ELSE 0 END)
 as performance')
            ->selectRaw("@departure_date := (select `getDepartureDate`(t.id))  'date_of_dept',
          DATEDIFF(@departure_date, DATE_FORMAT(t.created_at, '%Y-%m-%d')) 'difference'")
        ->selectRaw(" ( select ticket_fields.value tf from  ticket_fields where t.id = ticket_fields.ticket_id and (ticket_fields.name like '%Employee Name%' or ticket_fields.name like '%Vacation Requester%' ) ) employee_name");


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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $max = 0;
        $data = $this->query->get();
        $approvals_header = collect();

        $sheet->setTitle('New Report');

        $this->max_approvals = $data->max(function ($item) use ($max) {
            if ($item->approvals->count() > $max) {
                $max = $item->approvals->count();
            }
            return $max;
        });

        for ($i = 0; $i < $this->max_approvals; $i++) {
            $approvals_header->push(["Approval Level " . ($i + 1) . " Description",
                "Created Date",
                "Action Date",
                "Total Time (In days)",
                "Status",
                "Comment"
            ]);
        }


        $header = [
            'ID', 'Subject', 'Employee ID', 'Requester','Employee/Requester',
            'Technician', 'Category', 'Subcategory', 'Status', 'Created Date', 'Date of Departure', 'Days between departure and create', 'Due Date',
            'Resolved Date', 'First Approval Sent Date', 'Last Approval Action Date', 'Business Unit', 'Performance'];

        $approvals_header = $approvals_header->flatten()->toArray();
        $sheet->fromArray(array_merge($header, $approvals_header), NULL, 'A1', true);

        $row = 1;
        foreach ($data as $key => $ticket) {
            ++$row;

            $ticket_approvals = $ticket->approvals;
            $approvals = collect();
            if ($ticket_approvals->count()) {
                foreach ($ticket_approvals as $approval) {
                    $approvals->push([
                        str_replace('&nbsp;', ' ', strip_tags($approval->content)),
                        $approval->created_at,
                        $approval->approval_date,
                        $approval->approval_date ? $approval->approval_date->diffInDays($approval->created_at) : 0,
                        $approval->status_str,
                        str_replace('&nbsp;', ' ', strip_tags($approval->comment))
                    ]);
                }
            }

            $last_approval = $approvals->count() > 0 && $approvals->last()[2] ?
                $approvals->last()[2]->format('Y-m-d H:m') : 'Not Assigned';
            $first_approval = $approvals->count() > 0 && $approvals->last()[1] ?
                $approvals->last()[1]->format('Y-m-d H:m') : 'Not Assigned';
            $rowData = [
                $ticket->id, $ticket->subject, $ticket->employee_id, $ticket->requester,$ticket->employee_name, $ticket->technician,
                $ticket->category, $ticket->subcategory, $ticket->status, $ticket->created_at, $ticket->date_of_dept, $ticket->difference ?? 'Not Assigned',
                $ticket->due_date, $ticket->resolve_date, $first_approval, $last_approval, $ticket->business_unit, $ticket->performance
            ];
            $sheet->fromArray(array_merge($rowData, $approvals->flatten()->toArray()), NULL, 'A' . $row, true);
        }


        $highestColumn = $sheet->getHighestColumn(1);
        for ($column = 'A'; $column; $column++) {
            if ($column == $highestColumn) {
                break;
            }
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->setAutoFilter('A1:' . $highestColumn . '1');


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = storage_path('app/' . str_slug($this->report->title) . '.xlsx');
        $writer->save($filename);
        return $filename;
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

        for ($i = 0; $i < $this->max_approvals; $i++) {
            $approvals_header->push(["Approval Level " . ($i + 1) . " Sent At", "Action Date", "Status"]);
        }

        $columns = array_merge([
            'ID', 'Subject', 'Requester',
            'Technician', 'Category', 'Status', 'Created Date', 'Date of Departure', 'Difference', 'Due Date',
            'Resolved Date', 'Business Unit', 'Performance'
        ], $approvals_header->flatten()->toArray());


        $max = 0;
        $this->max_approvals = $data->max(function ($item) use ($max) {
            if ($item->approvals->count() > $max) {
                $max = $item->approvals->count();
            }
            return $max;
        });

        $content = view('emails.report.monthly_kpi_report_pdf', ['data' => $data, 'report' => $this->report, 'columns' => $columns, 'approvals_count' => $approvals_count]);

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
        if (!$this->parameters['start_date']) {
            $start_date = new Carbon('first day of last month');
        } else {
            $start_date = Carbon::parse($this->parameters['start_date']);
        }

        $start_date->setTime(0, 0, 0);
        $this->query->where('t.created_at', '>=', $start_date);


        if (!empty($this->parameters['end_date'])) {
            $end_date = Carbon::parse($this->parameters['end_date']);

        } else {
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