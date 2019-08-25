<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-08-25
 * Time: 11:30
 */

namespace App\Reports;
use Illuminate\Database\Query\Builder;

class MonthlyKPICategoryCustomized extends ReportContract
{
    /** @var Builder */
    protected $query;

    /** @var int */
    protected $row = 1;

    /** @var string */
    protected $view = 'reports.monthly_kpi_category_customized';

    function run()
    {
        // TODO: to be completed.
        $this->query = \DB::table('tickets as ti')
            ->leftJoin('ticket_approvals as ta','ta.ticket_id','=','ti.id')
            ->leftJoin('users as req','req.id','=','ti.requester_id')
            ->leftJoin('users as tech','tech.id','=','ti.technician_id')
            ->leftJoin('categories as c','c.id','=','ti.category_id')
            ->leftJoin('business_units as bu','req.business_unit_id','=','bu.id')
            ->leftJoin('slas as sla','sla.id','=','ti.sla_id');

    }

    function html()
    {
        // TODO: Implement html() method.
    }

    function excel()
    {
        // TODO: Implement excel() method.
    }

    function pdf()
    {
        // TODO: Implement pdf() method.
    }

    function csv()
    {
        // TODO: Implement csv() method.
    }
}