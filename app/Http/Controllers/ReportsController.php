<?php

namespace App\Http\Controllers;

use App\Category;
use App\CoreReport;
use App\Report;
use App\ReportFolder;
use App\Reports\CustomReport;
use App\Reports\QueryReport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Psy\Exception\FatalErrorException;

class ReportsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $this->authorize('reports');

        $reports = collect();

        if ($filters = \request('folder')) {
            $reports = Report::filter($filters)->get();

            $page = \request('page') ?: (Paginator::resolveCurrentPage() ?: 1);
            $reports = (new LengthAwarePaginator(
                $reports->forPage($page, 20)->values()->all(), $reports->count(), 20, $page, []))
                ->withPath('');
        }


        $folders = ReportFolder::orderBy('name')->get();

        $core_reports = CoreReport::orderBy('name')->get();

        return view('reports.index', compact('reports', 'core_reports', 'folders'));
    }

    function create()
    {
        $this->authorize('reports');

        $core_reports = CoreReport::where('name', '!=', 'Query Report')->orderBy('name')->get();
        $technicians = User::technicians()->orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $folders = auth()->user()->folders;
        return view('reports.standard_report.create', compact('core_reports', 'technicians', 'categories', 'folders'));
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
        $report->type = Report::$CORE_REPORT;
        $report->save();

        flash('Report Message', 'Report has been saved successfully', 'success');

        return \Redirect::route('reports.show', $report);
    }

    function show(Report $report, Request $request)
    {
        $this->authorize('show', $report);

        if ($request->has('filters')) {
            session()->put(str_slug(strtolower($report->title)) . '_filters', $request->filters);
        }

        if ($report->is_core_report) {
            $core_report_class = $report->core_report->class_name;
            $r = new $core_report_class($report);

        } elseif ($report->is_query_report) {
            $r = new QueryReport($report);
        } else {
            $r = new CustomReport($report);
        }

        if ($r->errors && $r->errors->count()) {
            return view('reports.errors', ['errors' => $r->errors]);
        }

        if (request()->exists('excel')) {
            $file = $r->excel();
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
//        $writer->save('export.xlsx');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . str_slug($report->title) . '.xlsx"');
            $writer->save("php://output");

        }
        if (request()->exists('pdf')) {
            $file = $r->pdf();

            if (!$file) {
                return;
            }

            $headers = array(
                'Content-Type: application/pdf',
            );

            return \Response::download($file, str_slug($report->title) . '.pdf', $headers);
        }


        return $r->html();
    }

    function edit(Report $report)
    {
        $this->authorize('edit', $report);


        if ($report->is_core_report) {
            $core_reports = CoreReport::where('name', '!=', 'Query Report')->orderBy('name')->get();
            $technicians = User::technicians()->orderBy('name')->get(['id', 'name']);
            $categories = Category::orderBy('name')->get(['id', 'name']);
            $folders = auth()->user()->folders;

            return view('reports.standard_report.edit', compact('report', 'core_reports', 'technicians', 'folders', 'categories'));
        } elseif ($report->is_query_report) {
            return redirect()->route('reports.query.edit', $report);
        } else {
            return redirect()->route('reports.custom_report.edit', $report);
        }

    }

    function update(Report $report, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'core_report_id' => 'required', 'folder_id' => 'required',
            'parameters.start_date' => 'required', 'parameters.end_date' => 'required'
        ]);

        $report->update($request->only('title', 'core_report_id', 'folder_id', 'parameters','type'));

        flash('Report Message', 'Report has been updated successfully', 'success');
        return \Redirect::route('reports.show', $report);
    }

    function delete()
    {

    }
}
