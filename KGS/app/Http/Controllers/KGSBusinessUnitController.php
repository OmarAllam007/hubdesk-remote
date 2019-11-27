<?php

namespace KGS\Http\Controllers;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\BusinessUnit;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use KGS\KGSBusinessUnit;
use KGS\Requirement;

class KGSBusinessUnitController extends Controller
{

    function index()
    {
        $business_units = KGSBusinessUnit::with('business_unit')->get();
        return view('kgs::admin.business_unit.index',compact('business_units'));
    }

    function edit()
    {
        $business_units = KGSBusinessUnit::all();
        return view("kgs::admin.business_unit.edit", compact('business_units'));
    }

    function update(Request $request)
    {
        $this->validate($request,['business_unit_id'=>'required'],['business_unit_id.required'=>'The BusinessUnit field is required']);

        KGSBusinessUnit::query()->truncate();

        foreach ($request->get('business_unit_id',[]) as $business_unit_id){
            KGSBusinessUnit::create([
                'business_unit_id'=>$business_unit_id
            ]);
        }

        flash(t('BusinessUnits Update'),t('BusinessUnits have been updated'), 'success');
        return \Redirect::route('kgs.admin.business_unit.index');

    }

}
