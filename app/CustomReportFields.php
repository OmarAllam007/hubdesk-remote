<?php


namespace App;


use Carbon\Carbon;

class CustomReportFields
{
    protected $fields;
    protected $ticket_fields = ['id', 'created_at',
        'updated_at', 'resolve_date', 'close_date'];
    protected $others = ['requester', 'technician', 'created_by', 'category', 'subcategory', 'item', 'group', 'business_unit', 'status', 'performance'];

    protected $query;
    protected $report;
    protected $is_grouping = false;

    protected $data;

    /**
     * CustomReportFields constructor.
     * @param $request
     */
    public function __construct($report)
    {
        $this->query = \DB::query();

        $this->fields = $report->parameters['fields'];
        $this->report = $report;

        $this->selectFromTickets();
        $this->selectFromOtherTable();
        $this->filteredByDate();

        if ($this->report->parameters['group_by']) {
            $this->groupBy();
        }


    }

    function selectFromTickets()
    {
        $ticket_fields = array_intersect($this->ticket_fields, $this->fields);
        $ticket_fields = array_map(function ($field) {
            return 'ti.' . $field;
        }, $ticket_fields);

        $this->query->select($ticket_fields)->from('tickets as ti');
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

        $this->query->whereDate('ti.' . $this->report->parameters['date_filters']['by'], '>=', $this->report->parameters['date_filters']['from'])
            ->whereDate('ti.' . $this->report->parameters['date_filters']['by'], '<=', $this->report->parameters['date_filters']['to'] ?? Carbon::now()->toDateTimeString());
    }

    function groupBy()
    {
//        dd($this->query->get(),ucfirst($this->report->parameters['group_by']));
        $this->data = $this->query->get()->groupBy(ucfirst($this->report->parameters['group_by']));
        $this->is_grouping = true;
    }

    public function getData()
    {
        if ($this->report->parameters['group_by']) {
            return $this->data;
        }
        $this->data = $this->query->paginate(20);

        return $this->data;

    }


}