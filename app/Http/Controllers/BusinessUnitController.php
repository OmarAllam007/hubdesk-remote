<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessUnitController extends Controller
{

	protected $rules = ['name' => 'required|unique:business_units,name', 'location_id' => 'required|exists:locations,id'];

	public function index()
	{
		$businessUnits = BusinessUnit::orderBy('name')->quickSearch()->paginate();

		return view('business-unit.index', compact('businessUnits'));
	}


	public function show(BusinessUnit $business_unit)
	{
		return view('business-unit.show', compact('business_unit'));
	}


}
