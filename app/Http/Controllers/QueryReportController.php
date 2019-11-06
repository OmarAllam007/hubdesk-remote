<?php

namespace App\Http\Controllers;

use App\Report;
use App\ReportFolder;
use App\Reports\QueryReport;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
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
        $this->validate($request, ['title' => 'required', 'folder_id' => 'required', 'query' => 'required']);

        if (!$this->valid_query($request->get('query'))) {
            return redirect()->route('reports.index');
        }

        $request['parameters'] = $request->filters;
        $request['type'] = Report::$QUERY_REPORT;
        $request['user_id'] = auth()->id();

        $report = Report::create($request->all());

        foreach ($request->users as $user) {
            $report->users()->create([
                'user_id' => $user
            ]);
        }

        return redirect()->route('reports.query_report.show', compact('report'));
    }


    public function show(Report $report, Request $request)
    {
        $r = new QueryReport($report);

        if ($request->exists('excel')) {
            return $r->excel()->download('xlsx');
        }

        return $r->html();
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

        if (!$this->valid_query($request->get('query'))) {
            return redirect()->route('reports.index');
        }

        $request['parameters'] = $request->filters;
        $request['type'] = Report::$QUERY_REPORT;
        $request['user_id'] = auth()->id();

        $report->update($request->all());

        $report->users()->delete();

        if (count($request->users)) {
            foreach ($request->users as $user) {
                $report->users()->create([
                    'user_id' => $user
                ]);
            }
        }

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
        if (!$parameters) {
            return [];
        }

        $params = [];
        foreach ($parameters as $parameter) {
            if ($parameter['type'] == 'date') {
                $date = \request('filters')[$parameter['name']];
                $params[$parameter['name']] = $date ? Carbon::parse($date)->format('Y-m-d h:s') : Carbon::now()->format('Y-m-d h:s');
            }
        }

        return $params;
    }

    private function valid_query($query)
    {
        return !str_contains($query, ['insert ', 'update ', 'delete ', 'truncate ']);
    }
}
