<?php

namespace App\Http\Controllers\Admin;

use App\Division;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    public function index()
    {
        $divisions = Division::orderBy('name')->quickSearch()->paginate();

        return view('admin.division.index', compact('divisions'));
    }

    public function create()
    {
        return view('admin.division.create');
    }

    public function store(Request $request)
    {
//        $this->validates($request);

        Division::create($request->all());

        flash(t('Division Info'), 'Division has been saved', 'success');

        return \Redirect::route('admin.division.index');
    }

    public function show(Division $division)
    {
        return view('admin.division.show', compact('division'));
    }

    public function edit(Division $division)
    {
        return view('admin.division.edit', compact('division'));
    }

    public function update(Division $division, Request $request)
    {
        $division->update($request->all());

        flash(t('Division Info'), t('Division has been saved'), 'success');

        return \Redirect::route('admin.division.index');
    }

    public function destroy(Division $division)
    {
        $division->delete();

        flash(t('Division Info'), 'Division has been deleted', 'error');

        return \Redirect::route('admin.division.index');
    }
}
