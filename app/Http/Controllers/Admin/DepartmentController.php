<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $rules = ['name' => 'required', 'business_unit_id' => 'required|exists:business_units,id'];

    public function index()
    {
        $departments = Department::orderBy('name')->quickSearch()->paginate();;

        return view('admin.department.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.department.create');
    }

    public function store(Request $request)
    {
        $this->validates($request);

        Department::create($request->all());

        flash(t('Department Info'),t('Department has been saved'), 'success');

        return \Redirect::route('admin.department.index');
    }

    public function show(Department $department)
    {
        return view('admin.department.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('admin.department.edit', compact('department'));
    }

    public function update(Department $department, Request $request)
    {
        $this->validates($request);

        $department->update($request->all());

        flash(t('Department'),t('Department has been saved'), 'success');

        return \Redirect::route('admin.department.index');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        flash(t('Department has been deleted'), 'success');

        return \Redirect::route('admin.department.index');
    }
}
