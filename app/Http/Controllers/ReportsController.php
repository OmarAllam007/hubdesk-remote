<?php

namespace App\Http\Controllers;

use App\Category;
use App\CoreReport;
use App\Report;
use App\ReportFolder;
use App\Reports\CustomReport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ReportsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $this->authorize('reports');

        $filters = ['folder' => request('folder')];

        $reports = Report::filter($filters)->get();
        $custom_reports = CustomReport::where('folder_id',request('folder'))->get();

        $reports = $reports->merge($custom_reports);

        $folders = ReportFolder::orderBy('name')->get();

        $core_reports = CoreReport::orderBy('name')->get();

        $page = \request('page') ?: (Paginator::resolveCurrentPage() ?: 1);

        $reports = (new LengthAwarePaginator(
            $reports->forPage($page, 20)->values()->all(), $reports->count(), 20, $page, []))
            ->withPath('');

        return view('reports.index', compact('reports', 'core_reports', 'folders'));
    }

    function create()
    {
        $this->authorize('reports');

        $core_reports = CoreReport::where('name', '!=', 'Query Report')->orderBy('name')->get();
        $technicians = User::technicians()->orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $folders = ReportFolder::orderBy('name')->get(['id', 'name']);

        return view('reports.create', compact('core_reports', 'technicians', 'categories', 'folders'));
    }

    function store(Request $request)
    {
        $this->authorize('reports');

        $this->validate($request, [
            'title' => 'required',
            'core_report_id' => 'required', 'folder_id' => 'required',
            'parameters.start_date' => 'required', 'parameters.end_date' => 'required'
        ]);

        $report = new Report($request->only('title', 'core_report_id', 'folder_id', 'parameters'));
        $report->user_id = auth()->id();
        $report->save();

        flash('Report has been saved', 'success');

        return \Redirect::route('reports.show', $report);
    }

    function show(Report $report)
    {
        $this->authorize('reports');
        
        $core_report_class = $report->core_report->class_name;

        $r = new $core_report_class($report);

        if (request()->exists('excel')) {
            return $r->excel();
        }

        return $r->html();
    }

    function edit()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }
}
