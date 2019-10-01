<?php

namespace App\Reports;

use App\Helpers\ChromePrint;
use App\Report;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Illuminate\Support\Facades\Storage;

class QueryReport extends ReportContract
{
    protected $columns;
    protected $row = 1;
    public $errors;

    function run()
    {
        try {
            $query = $this->parameters['query'] ?? $this->report['query'];
            $params = $this->parameters['parameters'] ?? $this->report['parameters'];
            $filters = request()->get('filters');

            if (!$filters) {
                $filters = $this->fillParameters($params);
            } else {
                $filters = $this->getBindingsParams($params, $filters);
            }

            $this->data = collect(\DB::select(\DB::raw($query), $filters));
            $this->columns = collect($this->data->first())->keys();
        } catch (\Illuminate\Database\QueryException  $e) {
            $errors = collect();
            $this->data = collect();
            $errors->push($e);
            $this->errors = $errors;
        }
    }


    function html()
    {
        $this->run();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $data = $this->data->forPage($page, $this->perPage);
        $pager = new LengthAwarePaginator($data, $this->data->count(), $this->perPage);

        $pager->setPath('');

        return view('reports.query_report.show', ['report' => $this->report, 'data' => $pager,
            'columns' => $this->columns]);
    }

    function excel()
    {
        $this->run();


        $data = $this->data;

        return \Excel::create(str_slug($this->report->title), function ($excel) use ($data) {
            /** @var LaravelExcelWriter $excel */

            $excel->sheet(ucwords($this->report->title), function ($sheet) use ($data) {
                /** @var LaravelExcelWorksheet $sheet */
                $sheet->row($this->row, collect($this->columns)->values()->toArray());

                $data->each(function ($ticket) use ($sheet) {
                    $ticket = collect($ticket);
                    $sheet->row(++$this->row, $ticket->toArray());
                });

                $sheet->setColumnFormat(["C2:D{$this->row}" => '#,##0.00']);
                $sheet->setColumnFormat(["E2:E{$this->row}" => '0.00%']);

                $sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
        });

    }

    function pdf()
    {
        $content = view('emails.report.pdf_report', ['report' => $this->report,
            'data' => $this->data, 'columns' => $this->columns]);

        $filepath = storage_path('app/' . uniqid('report') . '.html');
        file_put_contents($filepath, $content->render());

        $print = new ChromePrint($filepath);
        $file = $print->print();

        return $file;
    }

    function csv()
    {

    }

    private function getBindingsParams($params, $filters)
    {
        foreach ($filters as $key => $filter) {
            if ($params[$key]["type"] == "date") {
                $filters[$key] = Carbon::parse($filter, 'AST')->toDateTimeString();
            }
        }
        return $filters;
    }

    function getTypeAttribute()
    {
        return Report::$QUERY_REPORT;
    }

    private function fillParameters($params)
    {
        if (!$params) return [];
        $filters = [];

        foreach ($params as $key => $param) {
            if ($param["type"] == "date") {
                if (str_contains($param["name"], "from")) {
                    $filters[$param["name"]] =  new Carbon('first day of last month');
                } else if (str_contains($param["name"], "to")) {
                    {
                        $filters[$param["name"]] = new Carbon('last day of last month');
                    }
                }
            }
        }

        return $filters;
    }
}