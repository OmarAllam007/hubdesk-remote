<?php

namespace App\Http\Controllers\Admin;

use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    protected $rules = ['name' => 'required|unique:locations,name', 'city_id' => 'required|exists:cities,id'];

    public function index()
    {
        $locations = Location::orderBy('name')->quickSearch()->paginate();;

        return view('admin.location.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.location.create');
    }

    public function store(Request $request)
    {
        $this->validates($request, 'Could not save location');

        Location::create($request->all());

        flash(t('Location has been saved'), 'success');

        return \Redirect::route('admin.location.index');
    }

    public function show(Location $location)
    {
        return view('admin.location.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('admin.location.edit', compact('location'));
    }

    public function update(Location $location, Request $request)
    {
        $this->rules['name'] .= ','.$location->id;
        $this->validates($request, 'Could not save location');

        $location->update($request->all());

        flash(t('Location has been saved'), 'success');

        return \Redirect::route('admin.location.index');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        flash(t('Location has been deleted'), 'success');

        return \Redirect::route('admin.location.index');
    }
    
}
