<?php

namespace App\Http\Controllers\Admin;

use App\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    protected $rules = [];

    public function index()
    {
        $statuses = Status::paginate();

        return view('admin.status.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.status.create');
    }

    public function store(Request $request)
    {
        $this->validates($request);

        Status::create($request->all());

        flash(t('Status Info'),t('Status has been saved'), 'success');

        return \Redirect::route('admin.status.index');
    }

    public function show(Status $status)
    {
        return view('admin.status.show', compact('status'));
    }

    public function edit(Status $status)
    {
        return view('admin.status.edit', compact('status'));
    }

    public function update(Status $status, Request $request)
    {
        $this->validates($request);

        $status->update($request->all());

        flash(t('Status'),t('Status has been saved'), 'success');

        return \Redirect::route('admin.status.index');
    }

    public function destroy(Status $status)
    {
        $status->delete();

        flash(t('Status has been deleted'), 'success');

        return \Redirect::route('admin.status.index');
    }
}
