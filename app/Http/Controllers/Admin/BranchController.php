<?php

namespace App\Http\Controllers\Admin;

use App\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $rules = ['name' => 'required', 'location_id' => 'required|exists:locations,id', 'business_unit_id' => 'required|exists:business_units,id'];

    public function index()
    {
        $branches = Branch::orderBy('name')->quickSearch()->paginate();;

        return view('admin.branch.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branch.create');
    }

    public function store(Request $request)
    {
        $this->validates($request);

        Branch::create($request->all());

        flash(t('Branch Info'),t('Branch has been saved'), 'success');

        return \Redirect::route('admin.branch.index');
    }

    public function show(Branch $branch)
    {
        return view('admin.branch.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('admin.branch.edit', compact('branch'));
    }

    public function update(Branch $branch, Request $request)
    {
        $this->validates($request);

        $branch->update($request->all());

        flash(t('Branch Info'),t('Branch has been saved'), 'success');

        return \Redirect::route('admin.branch.index');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        flash(t('Branch Info'),t('Branch has been deleted'), 'success');

        return \Redirect::route('admin.branch.index');
    }
}
