<?php

namespace App\Http\Controllers\Admin;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\Category;
use App\ServiceUserGroup;
use App\Requirement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    protected $rules = ['name' => 'required'];

//'business_unit_id' => 'required|exists:business_units,id'

    public function index()
    {
        $categories = Category::orderBy('name')->quickSearch()->paginate();

        return view('admin.category.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $business_unit_id = 0;
        if ($request->has('business-unit')) {
            $business_unit_id = $request->get('business-unit');
        }

        return view('admin.category.create', compact('business_unit_id'));
    }

    public function store(Request $request)
    {
        $this->validates($request);

        $data = $request->all();
        $data['service_request'] = isset($request->service_request) ? 1 : 0;
        $data['is_disabled'] = isset($request->is_disabled) ? 1 : 0;

        $category = Category::create($data);

        if ($request['units']) {
            $category->businessunits()->sync($request['units']);
        }

        if ($request->hasFile('logo')) {
            $logo_path = Category::uploadAttachment($category, $request->logo);
            $category->update(['logo' => $logo_path]);
        }

        $this->handleLevels($request, $category);
        $this->createUserGroups($request, $category);
        $this->handleRequirements($request, $category);
        $this->createFees($request, $category);
        $this->createOrUpdateAvailability($request, $category);
        $this->createOrUpdateLimitation($request, $category);

        flash(t('Category Info'), t('Category has been saved'), 'success');

        return \Redirect::route('admin.category.index');
    }

    public function show(Category $category)
    {
        return view('admin.category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Category $category, Request $request)
    {
        $this->validates($request);


        if ($request['units']) {
            $category->businessunits()->sync($request['units']);
        }

        $this->createUserGroups($request, $category);
        $this->handleLevels($request, $category);
        $this->handleRequirements($request, $category);
        $this->createFees($request, $category);
        $this->createOrUpdateAvailability($request, $category);
        $this->createOrUpdateLimitation($request, $category);

        $data = $request->all();
        $data['service_request'] = isset($request->service_request) ? 1 : 0;
        $data['is_disabled'] = isset($request->is_disabled) ? 1 : 0;
        $category->update($data);

        if ($request->hasFile('logo')) {
            $logo_path = Category::uploadAttachment($category, $request->logo);
            $category->update(['logo' => $logo_path]);
        }

        flash(t('Category Info'), t('Category has been saved'), 'success');
        return \Redirect::route('admin.category.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        flash(t('Category Info'), t('Category has been deleted'), 'success');

        return \Redirect::route('admin.category.index');
    }


    private function createUserGroups(Request $request, Category $category)
    {

        $category->service_user_groups()->delete();
        foreach ($request->get('user_groups', []) as $group) {
            $category->service_user_groups()->create([
                'level' => ServiceUserGroup::$CATEGORY,
                'group_id' => $group
            ]);
        }

    }

    private function handleLevels(Request $request, Category $category)
    {
        $category->levels()->delete();

        foreach ($request->get('levels', []) as $key => $role) {
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

        foreach ($request->get('requirements', []) as $requirement) {
            $category->requirements()->create([
                'reference_type' => 1,
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

        foreach ($request->get('fees', []) as $fee) {
            $category->fees()->create([
                'name' => $fee['name'],
                'cost' => $fee['cost'],
                'level' => AdditionalFee::CATEGORY,
                'level_id' => $category->id,
            ]);
        }
    }

    private function createOrUpdateAvailability(Request $request, Category $category)
    {
        $category->availabilities()->delete();

        foreach ($request->get('availabilities', []) as $availability) {
            $category->availabilities()->create([
                'value' => explode(",", $availability['business_units_value']),
                'label' => $availability['business_units_label'],
                'type' => $availability['type'],
                'available_until' => $availability['availability_until'],
            ]);
        }

    }

    private function createOrUpdateLimitation(Request $request, Category $category)
    {
        $category->limitations()->delete();

        foreach ($request->get('limitations', []) as $limitation) {
            $category->limitations()->create([
                'value' => explode(",", $limitation['value']),
                'label' => $limitation['label'],
                'level' => get_class($category),
                'number_of_tickets' => $limitation['number_of_tickets'],
            ]);
        }

    }
}
