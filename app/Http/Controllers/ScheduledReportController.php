<?php

namespace App\Http\Controllers;

use App\Jobs\SendScheduleReport;
use App\Report;
use App\ScheduledReport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduledReportController extends Controller
{

    public function index()
    {
        $reports = ScheduledReport::paginate(25);
        return view('reports.scheduled_report.index', compact('reports'));
    }

    public function create()
    {
        $reports = Report::with('folder')->get()
            ->groupBy('folder.name');
        $users = User::query()->select(['id', 'name'])->get();

        return view('reports.scheduled_report.create', compact('reports', 'users'));
    }

    public function store(Request $request)
    {

        $request['user_id'] = $request->user()->id;
        ScheduledReport::create($request->all());

        return redirect()->route('reports.index');

    }


    public function show(ScheduledReport $report)
    {
        return view('reports.scheduled_report.show', compact('report'));
    }


    public function edit(ScheduledReport $report)
    {
        $reports = Report::with('folder')->get()
            ->groupBy('folder.name');
        $users = User::query()->select(['id', 'name'])->get();

        return view('reports.scheduled_report.edit', compact('report', 'reports', 'users'));
    }


    public function update(Request $request, ScheduledReport $report)
    {

        $request['user_id'] = $request->user()->id;
        $report->update($request->all());

        return redirect()->route('reports.index');
    }

    function execute(ScheduledReport $report)
    {
        dispatch(new SendScheduleReport($report));

        return redirect()->route('reports.scheduled_report.index');
    }

    public function destroy(ScheduledReport $scheduledReport)
    {
        //
    }
}
