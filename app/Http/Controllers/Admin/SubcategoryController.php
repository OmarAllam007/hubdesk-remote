<?php

namespace App\Http\Controllers\Admin;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\ServiceUserGroup;
use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubcategoryController extends Controller
{

    protected $rules = ['name' => 'required', 'category_id' => 'required|exists:categories,id'];

    public function index()
    {
        $subcategories = Subcategory::paginate();

        return view('admin.subcategory.index', compact('subcategories'));
    }

    public function create(Request $request)
    {
        $category_id = 0;
        if ($request->has('category')) {
            $category_id = $request->get('category');
        }

        return view('admin.subcategory.create', compact('category_id'));
    }

    public function store(Request $request)
    {
        $this->validates($request, 'Could not save category');

        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $subcategory = Subcategory::create($data);

        $this->handleLevels($request, $subcategory);
        $this->createUserGroups($request, $subcategory);
        $this->handleRequirements($request,$subcategory);
        $this->createFees($request,$subcategory);

        flash('Subcategory has been saved', 'success');

        return \Redirect::route('admin.category.show', $subcategory->category_id);
    }

    public function show(Subcategory $subcategory)
    {
        return view('admin.subcategory.show', compact('subcategory'));
    }

    public function edit(Subcategory $subcategory)
    {
        return view('admin.subcategory.edit', compact('subcategory'));
    }

    public function update(Subcategory $subcategory, Request $request)
    {
        $this->validates($request, 'Could not save category');
        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $subcategory->update($data);

        $this->handleLevels($request, $subcategory);
        $this->createUserGroups($request, $subcategory);

        $this->handleRequirements($request,$subcategory);
        $this->createFees($request,$subcategory);

        flash('Subcategory has been saved', 'success');
        return \Redirect::route('admin.category.show', $subcategory->category_id);
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        flash(t('Subcategory has been deleted'), 'success');

        return \Redirect::route('admin.category.show', $subcategory->category_id);
    }

    private function handleLevels(Request $request, Subcategory $subcategory)
    {
        $subcategory->levels()->delete();

        if (count($request->levels)) {
            foreach ($request->levels as $key => $role) {
                ApprovalLevels::create([
                    'type' => 2,
                    'level_id' => $subcategory->id,
                    'role_id' => $role,
                ]);
            }
        }
    }

    private function createUserGroups(Request $request,Subcategory $subcategory)
    {
        if (count($request['user_groups'])) {
            $subcategory->service_user_groups()->delete();
            foreach ($request['user_groups'] as $group) {
                $subcategory->service_user_groups()->create([
                    'level' => ServiceUserGroup::$SUBCATEGORY,
                    'group_id' => $group
                ]);
            }
        }
    }

    private function handleRequirements(Request $request, Subcategory $subcategory)
    {
        if(!count($request->requirements)){
            return;
        }

        $subcategory->requirements()->delete();

        foreach ($request->requirements as $requirement){
            $subcategory->requirements()->create([
                'reference_type'=> 2,
                'reference_id'=> $subcategory->id,
                'field'=>$requirement['field'],
                'operator'=>'is',
                'label'=>$requirement['label'],
                'value'=>$requirement['value'],
            ]);
        }

    }

    private function createFees(Request $request, Subcategory $subcategory)
    {
        if (!count($request->fees)) {
            return;
        }
        $subcategory->fees()->delete();

        foreach ($request->fees as $fee) {
            $subcategory->fees()->create([
                'name' => $fee['name'],
                'cost' => $fee['cost'],
                'level'=> AdditionalFee::SUBCATEGORY,
                'level_id'=> $subcategory->id,
            ]);
        }
    }
}
