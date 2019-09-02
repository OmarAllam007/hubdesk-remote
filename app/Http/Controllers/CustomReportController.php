<?php

namespace App\Http\Controllers;

use App\CustomReportFields;
use App\Report;
use App\ReportFolder;
use App\Reports\CustomReport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Matrix\Builder;

class CustomReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $folder = ReportFolder::all();

        return view('reports.custom_report.create', compact('folder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required', 'folder_id' => 'required|min:1', 'fields' => 'required']);


        $params = [];
        $params['fields'] = $request->get('fields');
        $params['date_filters'] = $request->get('date_filters');
        $params['group_by'] = $request->get('group_by');
        $params['summary_by'] = $request->get('summary_by');
        $params['business_unit_filter'] = $request->get('business_unit_filter');
        $params['technicians_filter'] = $request->get('technicians_filter');

        $report = CustomReport::create([
            'title' => $request->title,
            'folder_id' => $request->folder_id,
            'user_id' => auth()->id(),
            'parameters' => $params
        ]);


        return redirect()->route('reports.custom_report.show', compact('report'));
    }

    /**
     * Display the specified resource.
     *
     * @param CustomReport $report
     * @return \Illuminate\Http\Response
     */
    public function show(CustomReport $report)
    {
        $custom_report = new CustomReportFields($report);
        $data = $custom_report->getData();
        $graph_data = null;

        /** @var Collection $data */
        if ($report->parameters['group_by']) {
            $graph_data = $data->map(function ($value, $key) {
                return $graph_data[$key] = $value->count();
            })->toArray();
        }


        return view('reports.custom_report.show', compact('data', 'report', 'graph_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomReport $report)
    {
        $folder = ReportFolder::all();

        return view('reports.custom_report.edit', compact('folder', 'report'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomReport $report)
    {
//        dd($request->all());

        $this->validate($request, ['title' => 'required', 'folder_id' => 'required|min:1',
            'fields' => 'required','date_filters.from'=>'required'],['date_filters.from.required'=>'From date is required']);

        $params = [];
        $params['fields'] = $request->get('fields');
        $params['date_filters'] = $request->get('date_filters');
        $params['group_by'] = $request->get('group_by');
        $params['summary_by'] = $request->get('summary_by');
        $params['business_unit_filter'] = $request->get('business_unit_filter');
        $params['technicians_filter'] = $request->get('technicians_filter');

        $report->update([
            'title' => $request->title,
            'folder_id' => $request->folder_id,
            'user_id' => auth()->id(),
            'parameters' => $params
        ]);


        return redirect()->route('reports.custom_report.show', compact('report'));
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
}
