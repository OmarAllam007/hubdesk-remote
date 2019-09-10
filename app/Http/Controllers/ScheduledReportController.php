<?php

namespace App\Http\Controllers;

use App\ScheduledReport;
use Illuminate\Http\Request;

class ScheduledReportController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        return view('reports.scheduled_report.create');
    }

    public function store(Request $request)
    {
        //
    }


    public function show(ScheduledReport $scheduledReport)
    {
        //
    }


    public function edit(ScheduledReport $scheduledReport)
    {
        //
    }


    public function update(Request $request, ScheduledReport $scheduledReport)
    {
        //
    }

    public function destroy(ScheduledReport $scheduledReport)
    {
        //
    }
}
