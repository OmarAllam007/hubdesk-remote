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
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Workbook;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FullDetailsMonthlyReport extends ReportContract
{
    /** @var Builder */
    protected $query;

    /** @var int */
    protected $row = 1;

    /** @var string */

    protected $max_approvals = 0;
    protected $max_fields = 0;
    protected $max_replies = 0;
    /** @var Collection */
    protected $header = '';
    protected $view = 'reports.full_details_monthly_report';

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
//        dd($this->query->take(10)->get());
    }

    protected function fields()
    {
        $this->query->selectRaw('t.id as id, t.subject, t.created_at, t.due_date, t.resolve_date, 
        tech.name as technician, req.name as requester')
            ->selectRaw('t.sla_id, resolve_date, overdue, time_spent')
            ->selectRaw('cat.name as category, subcat.name as subcategory, item.name as item')
            ->selectRaw('st.name as status')
            ->selectRaw('bu.name as business_unit');


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


        return view($this->view, ['data' => $data, 'report' => $this->report, 'approvals_count' => $approvals_count]);
    }

    function excel()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $max_approvals = 0;
        $max_fields = 0;
        $max_replies = 0;
        $data = $this->query->get();

        $sheet->setTitle('New Report');

        $this->max_approvals = $data->max(function ($item) use ($max_approvals) {
            if ($item->approvals->count() > $max_approvals) {
                $max_approvals = $item->approvals->count();
            }
            return $max_approvals;
        });

        $this->max_fields = $data->max(function ($item) use ($max_fields) {
            if ($item->fields->count() > $max_fields) {
                $max_fields = $item->fields->count();
            }
            return $max_fields;
        });

        $this->max_replies = $data->max(function ($item) use ($max_replies) {
            if ($item->replies->count() > $max_replies) {
                $max_replies = $item->replies->count();
            }
            return $max_replies;
        });

        $this->header = collect([
            'ID', 'Subject', 'Requester',
            'Technician', 'Category', 'Status', 'Created Date', 'Date of Departure', 'Days between departure and create', 'Due Date',
            'Resolved Date', 'Business Unit']);


        $this->fillTicketDependencies($sheet, $data);

        $highestColumn = $sheet->getHighestColumn(1);
        for ($column = 'A'; $column; $column++) {
            if ($column == $highestColumn) {
                break;
            }
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->setAutoFilter('A1:' . $highestColumn . '1');


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
//        $writer->save('export.xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . str_slug($this->report->title) . '.xlsx"');
        $writer->save("php://output");
        exit;
//
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
            'Resolved Date', 'Business Unit'
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

    private function fillTicketDependencies(Worksheet $sheet,  Collection $data)
    {
        for ($i = 0; $i < $this->max_fields; $i++) {
            $this->header->push(["Field Name", "Field Value"]);
        }

        for ($i = 0; $i < $this->max_replies; $i++) {
            $this->header->push(["Reply Content", "Status"]);
        }

        for ($i = 0; $i < $this->max_approvals; $i++) {
            $this->header->push(["Approval Level " . ($i + 1) . " Sent At", "Action Date", "Status"]);
        }

        $sheet->fromArray($this->header->flatten()->toArray(), NULL, 'A1', true);

        $row = 1;

        foreach ($data as $key => $ticket) {
            ++$row;
            $row_fields = collect();

            if ($ticket->fields->count()) {
                foreach ($ticket->fields as $field) {
                    $row_fields->push([$field->name,
                        $field->value]);
                }
            }

            if($ticket->replies->count()){
                foreach ($ticket->replies as $reply) {
                    $row_fields->push([$reply->content,
                        $reply->status->name]);
                }
            }

            if ($ticket->approvals->count()) {
                foreach ($ticket->approvals as $approval) {
                    $row_fields->push([$approval->created_at->format('Y-m-d'),
                        $approval->approval_date ? $approval->approval_date->format('Y-m-d') : '',
                        $approval->status_str]);
                }
            }

//            dd($row_fields->flatten());

            $rowData = [
                $ticket->id, $ticket->subject, $ticket->requester, $ticket->technician,
                $ticket->category, $ticket->status, $ticket->created_at, $ticket->date_of_dept, $ticket->difference ?? 'Not Assigned',
                $ticket->due_date, $ticket->resolve_date, $ticket->business_unit
            ];

            $sheet->fromArray(array_merge($rowData, $row_fields->flatten()->toArray()), NULL, 'A' . $row, true);
        }

    }
}