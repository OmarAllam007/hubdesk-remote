<?php

namespace KGS\Http\Controllers;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use KGS\Requirement;

class KGSSubcategoryController extends Controller
{

    function index()
    {
        $subcategories = Subcategory::whereHas('category', function ($q) {
            return $q->where('business_unit_id', env('GS_ID'));
        })->paginate();

        return view("kgs::admin.subcategory.index", compact('subcategories'));
    }

    function edit(Subcategory $subcategory)
    {
        return view("kgs::admin.subcategory.edit", compact('subcategory'));
    }

    function create()
    {
        return view('kgs::admin.subcategory.create');
    }

    function store(Request $request)
    {
        $this->validate($request, ['category_id' => 'required', 'name' => 'required', 'description' => 'required'],
            ['category_id.required' => 'The category field is required.']);

        $subcategory = Subcategory::create($request->all());
        $this->handleLevels($request, $subcategory);
        $this->handleRequirements($request, $subcategory);
        $this->createFees($request, $subcategory);

        return redirect()->route('kgs.admin.subcategory.index');
    }


    function update(Subcategory $subcategory, Request $request)
    {
        $this->validate($request, ['category_id' => 'required', 'name' => 'required', 'description' => 'required'],
            ['category_id.required' => 'The category field is required.']);

        $subcategory->update($request->all());

        $this->handleLevels($request, $subcategory);
        $this->handleRequirements($request, $subcategory);
        $this->createFees($request, $subcategory);


        flash(t('Subcategory Saved'), t('Subcategory has been saved'), 'success');

        return \Redirect::route('kgs.admin.subcategory.index');

    }

    function show(Subcategory $subcategory)
    {
        return view("kgs::admin.subcategory.show", compact('subcategory'));

    }

    function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('kgs.admin.subcategory.index');
    }


    private function handleLevels(Request $request, Subcategory $subcategory)
    {
        $subcategory->levels()->delete();

        foreach ($request->get('levels', []) as $key => $role) {
            ApprovalLevels::create([
                'type' => ApprovalLevels::SUBCATEGORY_TYPE,
                'level_id' => $subcategory->id,
                'role_id' => $role,
            ]);
        }

    }

    private function handleRequirements(Request $request, Subcategory $subcategory)
    {
        $subcategory->requirements()->delete();

        foreach ($request->get('requirements', []) as $requirement) {

            $subcategory->requirements()->create([
                'reference_type' => Requirement::SUBCATEGORY_TYPE,
                'reference_id' => $subcategory->id,
                'field' => $requirement['field'],
                'operator' => 'is',
                'label' => $requirement['label'],
                'value' => $requirement['value'],
                'type' => $requirement['type']
            ]);
        }

    }


    private function createFees(Request $request, Subcategory $subcategory)
    {
        $subcategory->fees()->delete();

        foreach ($request->get('fees', []) as $fee) {
            $subcategory->fees()->create([
                'name' => $fee['name'],
                'cost' => $fee['cost'],
                'level' => AdditionalFee::SUBCATEGORY,
                'level_id' => $subcategory->id,
            ]);
        }
    }
}
