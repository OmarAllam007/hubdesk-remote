<?php

namespace App\Http\Controllers\Admin;

use App\ApprovalLevels;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    protected $rules = ['name' => 'required', 'business_unit_id' => 'required|exists:business_units,id'];


    public function index()
    {
        $categories = Category::orderBy('name')->quickSearch()->paginate();;

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

        $this->validates($request, 'Could not save category');

        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $category = Category::create($data);

        if ($request['units']) {
            $category->businessunits()->sync($request['units']);
        }

        $this->handleLevels($request,$category);

        flash(t('Category has been saved'), 'success');

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
        $this->validates($request, 'Could not save category');
        if ($request['units']) {
            $category->businessunits()->sync($request['units']);
        }


        $this->handleLevels($request,$category);

        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $category->update($data);
        flash(t('Category has been saved'), 'success');
        return \Redirect::route('admin.category.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        flash(t('Category has been deleted'), 'success');

        return \Redirect::route('admin.category.index');
    }

    private function handleLevels(Request $request,Category $category)
    {
        $category->levels()->delete();

        if (count($request->levels)) {
            foreach ($request->levels as $key => $role) {
                ApprovalLevels::create([
                    'type' => 1,
                    'level_id' => $category->id,
                    'role_id' => $role,
                ]);
            }
        }
    }
}
