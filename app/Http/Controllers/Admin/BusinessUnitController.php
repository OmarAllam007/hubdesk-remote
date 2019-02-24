<?php

namespace App\Http\Controllers\Admin;

use App\BusinessUnit;
use App\BusinessUnitRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessUnitController extends Controller
{
    protected $rules = ['name' => 'required|unique:business_units,name', 'location_id' => 'required|exists:locations,id'];

    public function index()
    {
        $businessUnits = BusinessUnit::orderBy('name')->quickSearch()->paginate();

        return view('admin.business-unit.index', compact('businessUnits'));
    }

    public function create()
    {
        return view('admin.business-unit.create');
    }

    public function store(Request $request)
    {
        alert()->flash(t('Business unit Info'), 'error', [
            'text' => t('Could not save business unit'),
            'timer' => 3000
        ]);

        $this->validates($request, null);


        $business_unit = BusinessUnit::create($request->all());



        if ($request->hasFile('logo_img')) {
            $path = BusinessUnit::uploadLogo($business_unit, $request->logo_img);
            $business_unit->update(['logo' => $path]);
        }

        alert()->flash(t('Business unit Info'), 'success', [
            'text' => t('Business unit has been saved'),
            'timer' => 3000
        ]);

        return \Redirect::route('admin.business-unit.index');
    }

    public function show(BusinessUnit $business_unit)
    {
        return view('admin.business-unit.show', compact('business_unit'));
    }

    public function edit(BusinessUnit $business_unit)
    {
        return view('admin.business-unit.edit', compact('business_unit'));
    }

    public function update(BusinessUnit $business_unit, Request $request)
    {
        $this->rules['name'] .= ',' . $business_unit->id;

        alert()->flash(t('Business unit Info'), 'error', [
            'text' => 'Could not save business unit',
            'timer' => 3000
        ]);

        $this->validates($request, null);

        $path = $business_unit->logo;

        if ($request->hasFile('logo_img')) {
            $path = BusinessUnit::uploadLogo($business_unit, $request->logo_img);
        }

        $business_unit->roles()->delete();

        if (count($request->roles)) {
            foreach ($request->roles as $key => $role) {
                BusinessUnitRole::create([
                    'business_unit_id' => $request->id,
                    'role_id' => $role['role_id'],
                    'user_id' => $role['user_id'],
                ]);
            }
        }


        $request['logo'] = $path;

        $business_unit->update($request->all());

        alert()->flash('Business unit Info', 'success', [
            'text' => 'Business unit has been saved',
            'timer' => 3000
        ]);

        return \Redirect::route('admin.business-unit.index');
    }

    public function destroy(BusinessUnit $business_unit)
    {
        $business_unit->delete();

        alert()->flash('Business unit Info', 'success', [
            'text' => 'Business Unit has been deleted',
            'timer' => 3000
        ]);

        return \Redirect::route('admin.business-unit.index');
    }


}
