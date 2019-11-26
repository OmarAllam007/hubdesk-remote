<?php

namespace KGS\Http\Controllers;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use KGS\Requirement;

class KGSCategoryController extends Controller
{

    function index()
    {
        $categories = Category::where('business_unit_id', env('GS_ID'))->paginate();
        return view("kgs::admin.category.index", compact('categories'));
    }

    function edit(Category $category)
    {
        return view("kgs::admin.category.edit", compact('category'));
    }

    function update(Category $category, Request $request)
    {
        $this->handleLevels($request, $category);
        $this->handleRequirements($request, $category);
        $this->createFees($request, $category);

        $data = $request->all();

        $category->update($data);

        flash(t('Category has been saved'), 'success');
        return \Redirect::route('kgs.admin.category.index');

    }

    function show(Category $category){

        return view("kgs::admin.category.show", compact('category'));
    }


    private function handleLevels(Request $request, Category $category)
    {
        $category->levels()->delete();

        if (empty($request->levels)) {
            return;
        }

        foreach ($request->levels as $key => $role) {
            ApprovalLevels::create([
                'type' => 1,
                'level_id' => $category->id,
                'role_id' => $role,
            ]);
        }

    }

    private function handleRequirements(Request $request, Category $category)
    {
        $category->requirements()->delete();

        if (empty($request->requirements)) {
            return;
        }

        foreach ($request->requirements as $requirement) {
            $category->requirements()->create([
                'reference_type' => Requirement::CATEGORY_TYPE,
                'reference_id' => $category->id,
                'field' => $requirement['field'],
                'operator' => 'is',
                'label' => $requirement['label'],
                'value' => $requirement['value'],
                'type' => $requirement['type']
            ]);
        }

    }


    private function createFees(Request $request, Category $category)
    {
        $category->fees()->delete();
        if (empty($request->fees)) {
            return;
        }

        foreach ($request->fees as $fee) {
            $category->fees()->create([
                'name' => $fee['name'],
                'cost' => $fee['cost'],
                'level' => AdditionalFee::CATEGORY,
                'level_id' => $category->id,
            ]);
        }
    }
}
