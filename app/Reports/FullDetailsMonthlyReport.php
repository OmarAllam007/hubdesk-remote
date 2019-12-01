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
use App\TicketApproval;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Workbook;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FullDetailsMonthlyReport extends ReportContract
{
    /** @var Builder */
    protected $query;

    /** @var int */
    protected $row = 2;

    /** @var string */

    protected $max_approvals = 0;
    protected $max_fields = 0;
    protected $max_replies = 0;
    protected $max_ticket_details = 0;
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

        $this->header = collect([
            'ID', 'Subject', 'Requester',
            'Technician', 'Category', 'Status', 'Created Date', 'Due Date',
            'Resolved Date', 'Business Unit']);

        $data = $this->query->get();
        $max_approvals = 0;
        $max_fields = 0;
        $max_replies = 0;

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

        $this->max_approvals = $data->max(function ($item) use ($max_approvals) {
            if ($item->approvals->count() > $max_approvals) {
                $max_approvals = $item->approvals->count();
            }
            return $max_approvals;
        });
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


        return view($this->view, ['data' => $data,
            'report' => $this->report,
            'fields_count'=>$this->max_fields,
            'replies_count'=>$this->max_replies,
            'approvals_count' => $this->max_approvals,
        ]);
    }

    function excel()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $data = $this->query->get();

        $sheet->setTitle('New Report');



        $this->fillTicketDependencies($sheet, $data);

        $highestColumn = $sheet->getHighestColumn(1);
        for ($column = 'A'; $column; $column++) {
            if ($column == $highestColumn) {
                break;
            }
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

//        $sheet->setAutoFilter('A2:' . $highestColumn . '2');

        $t_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details);
        $sheet->mergeCells("A1:".$t_index."1");

        $f_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details+1);
        $f_end_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details+($this->max_fields*2));
        $sheet->mergeCells($f_index."1:".$f_end_index."1");

        $r_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details+1+($this->max_fields*2));
        $r_end_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details+($this->max_fields*2)+($this->max_replies*2));
        $sheet->mergeCells($r_index."1:".$r_end_index."1");


        $a_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details+1+($this->max_fields*2)+($this->max_replies*2));
        $a_end_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->max_ticket_details+($this->max_fields*2)+($this->max_replies*2)+($this->max_approvals*3));
//        dd($a_index,$a_end_index);
        $sheet->mergeCells($a_index."1:".$a_end_index."1");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = storage_path('app/'.str_slug($this->report->title).'.xlsx');
        $writer->save($filename);
        return $filename;

//
    }

    private function fillTicketDependencies(Worksheet $sheet, Collection $data)
    {

        for ($i = 1; $i <= $this->max_fields; $i++) {
            $this->header->push(["Field Name", "Field Value"]);
        }

        for ($i = 1; $i <= $this->max_replies; $i++) {
            $this->header->push(["Reply Content", "Status"]);
        }

        for ($i = 1; $i <= $this->max_approvals; $i++) {
            $this->header->push(["Approval Level " . ($i) . " Sent At", "Action Date", "Status"]);
        }


        $sheet->fromArray($this->header->flatten()->toArray(), NULL, 'A2', true);

        $row = 2;
        $column = 1;

        foreach ($data as $key => $ticket) {
            $ticketDetails = [
                $ticket->id, $ticket->subject, $ticket->requester, $ticket->technician,
                $ticket->category, $ticket->status, $ticket->created_at,
                $ticket->due_date, $ticket->resolve_date, $ticket->business_unit
            ];

            $this->max_ticket_details = count($ticketDetails);

            ++$row;
            for ($t = 0; $t < count($ticketDetails); $t++) {
                $sheet->setCellValueByColumnAndRow($column, $row, $ticketDetails[$t]);
                $column++;
            }
            $sheet->setCellValueByColumnAndRow(1, 1, 'Ticket Details');


            $fields = $ticket->fields->pluck('name', 'value')->toArray();
            $field_values = array_values($fields);
            $field_names = array_keys($fields);
            $sheet->setCellValueByColumnAndRow($column, 1, 'Ticket Additional Fields');


            for ($f = 0; $f < $this->max_fields; $f++) {
                if (!isset($field_values[$f])) {
                    $column += 2;
                    continue;
                }
                $sheet->setCellValueByColumnAndRow($column, $row, $field_values[$f]);
                $column++;
                $sheet->setCellValueByColumnAndRow($column, $row, $field_names[$f]);
                $column++;
            }


            $replies = $ticket->replies()->get()->map(function ($reply) {
                return [strip_tags($reply->content), $reply->status->name];
            })->toArray();
            $sheet->setCellValueByColumnAndRow($column, 1, 'Ticket Replies');

            for ($r = 0; $r < $this->max_replies; $r++) {
                if (!isset($replies[$r])) {
                    $column += 2;
                    continue;
                }

                $sheet->setCellValueByColumnAndRow($column, $row, $replies[$r][0]);
                $column++;
                $sheet->setCellValueByColumnAndRow($column, $row, $replies[$r][1]);
                $column++;
            }

            $approvals = $ticket->approvals->toArray();
            $sheet->setCellValueByColumnAndRow($column, 1, 'Ticket Approvals');

            for ($r = 0; $r < $this->max_approvals; $r++) {
                if (!isset($approvals[$r])) {
                    $column += 3;
                    continue;
                }
                $sheet->setCellValueByColumnAndRow($column, $row, Carbon::parse($approvals[$r]['created_at'])->format('Y-m-d'));
                $column++;
                $sheet->setCellValueByColumnAndRow($column, $row, Carbon::parse($approvals[$r]['approval_date'])->format('Y-m-d'));
                $column++;
                $sheet->setCellValueByColumnAndRow($column, $row, TicketApproval::$statuses[$approvals[$r]['status']]);
                $column++;
            }

            $column = 1;
        }



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


}