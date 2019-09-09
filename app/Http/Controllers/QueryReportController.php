<?php

namespace App\Http\Controllers;

use App\Report;
use App\ReportFolder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class QueryReportController extends Controller
{

    public function index()
    {

    }

    public function create()
    {
        $folders = ReportFolder::all()->pluck('name', 'id')->toArray();
        return view('reports.query_report.create', compact('folders'));
    }


    public function store(Request $request)
    {
        $request['core_report_id'] = 1;
        $request['parameters'] = $request->filters;

        $report = Report::create($request->all());

        return redirect()->route('reports.query_report.show', compact('report'));
    }


    public function show(Report $report, Request $request)
    {
        $data = [];
        $columns = [];
        if (count($request->filters)) {
            $data = $this->prepareReportData($report);
            $columns = collect($data->first())->keys();

        }
        $page = \request('page') ?: (Paginator::resolveCurrentPage() ?: 1);

        $data = (new LengthAwarePaginator(
            collect($data)->forPage($page, 20)->values()->all(), collect($data)->count(), 20, $page, []))
            ->withPath('');

        return view('reports.query_report.show', compact('report', 'data', 'columns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Report $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        return view('reports.query_report.edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $this->validate($request, ['title' => 'required', 'folder_id' => 'required', 'query' => 'required']);

        $report->update($request->all());
        return redirect()->route('reports.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function prepareReportData(Report $report)
    {
        $params = $this->prepareParams($report->parameters);

        try {

            $data = \DB::select(\DB::raw($report->query), $params);

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

        return collect($data);
    }

    private function prepareParams($parameters)
    {
        $params = [];
        foreach ($parameters as $parameter) {
            if ($parameter['type'] == 'date') {
                $date = \request('filters')[$parameter['name']];
                $params[$parameter['name']] = $date ? Carbon::parse($date)->format('Y-m-d h:s') : Carbon::now()->format('Y-m-d h:s');
            }
        }

        return $params;
    }
}
