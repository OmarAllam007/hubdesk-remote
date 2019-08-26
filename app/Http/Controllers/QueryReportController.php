<?php

namespace App\Http\Controllers;

use App\Report;
use App\ReportFolder;
use Illuminate\Http\Request;

class QueryReportController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        $folders = ReportFolder::all()->pluck('name','id')->toArray();
//        dd($folders);
        return view('reports.query_report.create',compact('folders'));
    }


    public function store(Request $request)
    {

    }


    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        return view('reports.query_report.edit',compact('report'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $this->validate($request,['title'=>'required','folder_id'=>'required','query'=>'required']);
        $report->update($request->all());

        return redirect()->route('reports.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
