<?php

namespace App\Http\Controllers\Admin;

use App\Priority;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriorityController extends Controller
{

    protected $rules = ['name' => 'required'];

    public function index()
    {
        $priorities = Priority::orderBy('name')->quickSearch()->paginate();

        return view('admin.priority.index', compact('priorities'));
    }

    public function create()
    {
        return view('admin.priority.create');
    }

    public function store(Request $request)
    {
        $this->validates($request);

        Priority::create($request->all());

        flash(t('Priority Info'),t('Priority has been saved'), 'success');

        return \Redirect::route('admin.priority.index');
    }

    public function show(Priority $priority)
    {
        return view('admin.priority.show', compact('priority'));
    }

    public function edit(Priority $priority)
    {
        return view('admin.priority.edit', compact('priority'));
    }

    public function update(Priority $priority, Request $request)
    {
        $this->validates($request);

        $priority->update($request->all());

        flash(t('Priority Info'),t('Priority has been saved'), 'success');

        return \Redirect::route('admin.priority.index');
    }

    public function destroy(Priority $priority)
    {
        $priority->delete();

        flash(t('Priority Info'),t('Priority has been deleted'), 'success');

        return \Redirect::route('admin.priority.index');
    }
}
