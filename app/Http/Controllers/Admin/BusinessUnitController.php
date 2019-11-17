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
        flash(t('Business unit Info'),t('Could not save business unit') ,'error');
        $this->validates($request, null);
        $business_unit = BusinessUnit::create($request->all());



        if ($request->hasFile('logo_img')) {
            $logo_path = BusinessUnit::uploadAttachment($business_unit, $request->logo_img);
            $business_unit->update(['logo' => $logo_path]);
        }

        if ($request->hasFile('business_unit_bgd')) {
            $bgd_path = BusinessUnit::uploadAttachment($business_unit, $request->business_unit_bgd);
            $business_unit->update(['business_unit_bgd' => $bgd_path]);
        }

        flash(t('Business unit Info'),t('Business unit has been saved'), 'success');
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

        flash(t('Business unit Info'),t('Could not save business unit'), 'error');

        $this->validates($request, null);

        $path = $business_unit->logo;
        $bgd_path = $business_unit->business_unit_bgd_img;

        if ($request->hasFile('logo_img')) {
            $path = BusinessUnit::uploadAttachment($business_unit, $request->logo_img);
            $request['logo'] = $path;
        }

        if ($request->hasFile('business_unit_bgd_img')) {
            $bgd_path = BusinessUnit::uploadAttachment($business_unit, $request->business_unit_bgd_img);
            $request['business_unit_bgd'] = $bgd_path;
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

        $business_unit->update($request->all());

        flash('Business unit Info',t('Business unit has been saved'), 'success');

        return \Redirect::route('admin.business-unit.index');
    }

    public function destroy(BusinessUnit $business_unit)
    {
        $business_unit->delete();

        flash(t('Business unit Info'),t('Business Unit has been deleted'), 'success');

        return \Redirect::route('admin.business-unit.index');
    }


}
