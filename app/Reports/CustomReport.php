<?php

namespace App\Reports;

use App\Helpers\ChromePrint;
use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use PhpOffice\PhpSpreadsheet\Style\Color;


class CustomReport extends ReportContract
{
    use SoftDeletes;

    protected $fillable = ['title', 'folder_id', 'user_id', 'parameters'];

    protected $casts = ['parameters' => 'array'];

    protected $fields;
    protected $ticket_fields = ['id', 'created_at',
        'updated_at', 'resolve_date', 'close_date'];
    protected $others = ['requester', 'technician', 'created_by', 'category', 'subcategory',
        'item', 'group', 'business_unit', 'status', 'performance'];

    protected $query;
    public $report;

    protected $is_grouping = false;
    public $data;
    public $row = 1;
    public $columns = [];


    function user()
    {
        return $this->belongsTo(User::class);
    }

    function getTypeAttribute()
    {
        return Report::$CUSTOM_REPORT;
    }

    function run()
    {
        $this->query = \DB::query();
        $this->fields = $this->report->parameters['fields'];

        $this->selectFromTickets();
        $this->selectFromOtherTable();
        $this->filteredByDate();
        $this->filterByTechnicians();
        $this->filterByBusinessUnit();

        if ($this->report->parameters['group_by']) {
            $this->groupBy();
        }

    }

    function html()
    {
        $this->run();
//        $this->paginate();

        $graph_data = null;
        /** @var Collection $data */
        if ($this->report->parameters['group_by']) {
            $graph_data = $this->data->map(function ($value, $key) {
                return $graph_data[$key] = $value->count();
            })->toArray();
        }
        $report = $this->report;

        if ($this->report->parameters['group_by']) {
//            $this->groupBy();
            $data = $this->data;
        }else{
            $data = $this->paginate();
        }


        return view('reports.custom_report.show', compact('data', 'report', 'graph_data'));
    }

    function excel()
    {
        $this->run();

        if (!$this->is_grouping) {
            $this->getData();
        }

        $data = $this->data;

        /** @var Collection $data */
        return \Excel::create(str_slug($this->report->title), function ($excel) use ($data) {
            /** @var LaravelExcelWriter $excel */
            $excel->sheet(ucwords($this->report->title), function ($sheet) use ($data) {

                $sheet->row($this->row, $this->columns);

                if ($this->is_grouping) {
                    /** @var LaravelExcelWorksheet $sheet */
                    $data->each(function ($items, $key) use ($sheet) {

                        $sheet->row(++$this->row, [$key]);

                        $items_header_range = "A{$this->row}:{$sheet->getHighestDataColumn()}$this->row";
                        $sheet->mergeCells($items_header_range);

                        $sheet->getStyle($items_header_range)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
                        $sheet->getStyle($items_header_range)->getFill()->applyFromArray([
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'startcolor' => [
                                'rgb' => "194F7E"
                            ],
                        ]);

                        $sheet->getRowDimension($items_header_range)->setVisible(false)->setCollapsed(true);


                        $items->each(function ($item) use ($sheet) {
                            $sheet->row(++$this->row, collect($item)->toArray());
                        });
                    });
                } else {

                    $sheet->row($this->row, $this->columns);
                    $data->each(function ($item, $key) use ($sheet) {
                        $sheet->row(++$this->row, collect($item)->toArray());
                    });
                }

                $sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
//            $excel->download('xlsx');
        });
    }

    function pdf()
    {
        $this->run();

        $report = $this->report;
        if (!$this->is_grouping) {
            $this->getData();
        }
        $data = $this->data->flatten();

        $content = view('emails.report.pdf_report', ['report' => $report,
            'data' => $data, 'columns' => $this->columns]);

        $filepath = storage_path('app/' . uniqid('report') . '.html');
        file_put_contents($filepath, $content->render());

        $print = new ChromePrint($filepath);
        $file = $print->print();

        return $file;
    }

    function csv()
    {

    }


    function selectFromTickets()
    {
        $ticket_fields = array_intersect($this->ticket_fields, $this->fields);
        $ticket_fields = array_map(function ($field) {
            return 'ti.' . $field;
        }, $ticket_fields);

        $this->query->select($ticket_fields)->from('tickets as ti')->whereNotNull('ti.technician_id');
    }

    function selectFromOtherTable()
    {
        $other_fields = array_intersect($this->others, $this->fields);
        if (!count($other_fields)) {
            return;
        }

        foreach ($other_fields as $field) {
            if (method_exists($this, $field)) {
                $this->$field();
            }
        }
    }

    function requester()
    {
        $this->query
            ->leftJoin('users as req', 'req.id', '=', 'ti.requester_id')
            ->addSelect('req.name as Requester');

    }

    function technician()
    {
        $this->query
            ->leftJoin('users as tech', 'tech.id', '=', 'ti.technician_id')
            ->addSelect('tech.name as Technician');
    }

    function business_unit()
    {
        $this->query
            ->leftJoin('business_units as bu', 'bu.id', '=', 'req.business_unit_id')
            ->addSelect('bu.name as Business Unit');
    }

    function created_by()
    {
        $this->query
            ->leftJoin('users as creator', 'creator.id', '=', 'ti.creator_id')
            ->addSelect('creator.name as Created By');
    }

    function category()
    {
        $this->query
            ->leftJoin('categories as ca', 'ca.id', '=', 'ti.category_id')
            ->addSelect('ca.name as Category');
    }

    function subcategory()
    {
        $this->query
            ->leftJoin('subcategories as sub', 'sub.id', '=', 'ti.subcategory_id')
            ->addSelect('sub.name as Subcategory');
    }

    function item()
    {
        $this->query
            ->leftJoin('items as items', 'items.id', '=', 'ti.item_id')
            ->addSelect('items.name as Item');
    }

    function status()
    {
        $this->query
            ->leftJoin('statuses as st', 'st.id', '=', 'ti.status_id')
            ->addSelect('st.name as Status');
    }

    function group()
    {
        $this->query
            ->leftJoin('groups as gr', 'gr.id', '=', 'ti.group_id')
            ->addSelect('gr.name as Group');
    }

    function performance()
    {

    }

    function filteredByDate()
    {
        if (!$this->report->parameters['date_filters']['by']) {
            return;
        }

        $this->query->whereDate('ti.' . $this->report->parameters['date_filters']['by'], '>=', $this->report->parameters['date_filters']['from'] ?? Carbon::now()->firstOfMonth()->toDateTimeString())
            ->whereDate('ti.' . $this->report->parameters['date_filters']['by'], '<=', $this->report->parameters['date_filters']['to'] ?? Carbon::now()->lastOfMonth()->toDateTimeString());

    }

    function groupBy()
    {

            $this->data = $this->query->get()->groupBy(ucfirst($this->report->parameters['group_by']));
            $this->columns = array_keys(collect($this->query->first())->toArray());
            $this->is_grouping = true;
//        }
    }

    public function getData()
    {
        $this->data = $this->query->get();
        $this->columns = collect($this->data->first())->keys()->toArray();

//        dd($this->columns);
    }

    function filterByTechnicians()
    {
        if (isset($this->report->parameters['technicians_filter']) && count($this->report->parameters['technicians_filter'])) {
            $this->query->whereIn('ti.technician_id', $this->report->parameters['technicians_filter']);
        }

    }

    function filterByBusinessUnit()
    {
        if (isset($this->report->parameters['business_unit_filter']) && count($this->report->parameters['business_unit_filter'])) {
            $this->query->whereIn('ca.business_unit_id', $this->report->parameters['business_unit_filter']);
        }
    }

    private function paginate()
    {
        return $this->query->paginate(20);
    }
}