<?php

namespace App\Reports;

use App\Helpers\ChromePrint;
use App\Report;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class QueryReport extends ReportContract
{
    public $columns;
    public $row = 1;
    public $errors;
    public $data;
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

            $session_params = session(str_slug(strtolower($this->report->title)) . '_filters');

            if (request()->exists('excel') && !empty($session_params)) {
                $filters = $this->getBindingsParams($params, $session_params);
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($this->report->title);
        $sheet->fromArray(collect($this->columns)->values()->toArray(), NULL, 'A1', true);


        foreach ($data as $key => $ticket) {

            $ticket = collect($ticket)->map(function ($data) {
                return strip_tags($data);
            });
            $sheet->fromArray($ticket->toArray(),NULL,'A'.++$this->row,true);
        }


        $highestColumn = $sheet->getHighestColumn(1);
        for ($column = 'A'; $column ; $column++) {
            if($column == $highestColumn){
                break;
            }
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->setAutoFilter('A1:' . $highestColumn . '1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = storage_path('app/'.str_slug($this->report->title).'.xlsx');
        $writer->save($filename);
        return $filename;



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
        foreach ($params as $key => $filter) {
            if ($params[$key]["type"] == "date") {
                $filters[$params[$key]['name']] = Carbon::parse($filters[$params[$key]['name']], 'AST')->toDateTimeString();
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
                    $fday = new Carbon('first day of last month');
                    $filters[$param["name"]] = $fday->format('Y-m-d 00:00:00');

                } else if (str_contains($param["name"], "to")) {
                    {
                        $lday = new Carbon('last day of last month');
                        $filters[$param["name"]] = $lday->format('Y-m-d 00:00:00');
                    }
                }
            } else {
                $filters[$param["name"]] = 31;
            }

        }

        return $filters;
    }
}