<?php

namespace App\Http\Controllers;

use App\Report;
use App\ScheduledReport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduledReportController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        $reports = Report::with('folder')->get()
            ->groupBy('folder.name');
        $users = User::query()->select(['id','name'])->get();

        return view('reports.scheduled_report.create', compact('reports','users'));
    }

    public function store(Request $request)
    {
        ScheduledReport::create($request->all());

        return redirect()->route('reports.index');

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
