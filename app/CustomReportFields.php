<?php


namespace App;


use Carbon\Carbon;

class CustomReportFields
{
    protected $fields;
    protected $ticket_fields = ['id', 'created_at',
        'updated_at', 'resolve_date', 'close_date'];
    protected $others = ['requester', 'technician', 'created_by', 'category', 'subcategory',
        'item', 'group', 'business_unit', 'status', 'performance'];

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


    }


}