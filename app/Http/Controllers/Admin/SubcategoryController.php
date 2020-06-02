<?php

namespace App\Http\Controllers\Admin;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\Category;
use App\Complaint;
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
        $this->validates($request);

        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $subcategory = Subcategory::create($data);

        $this->handleLevels($request, $subcategory);
        $this->createUserGroups($request, $subcategory);
        $this->handleRequirements($request, $subcategory);
        $this->createFees($request, $subcategory);
        $this->createOrUpdateLimitation($request, $subcategory);

        flash(t('Subcategory Info'), 'Subcategory has been saved', 'success');

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
        $this->validates($request);
        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $subcategory->update($data);

        $this->handleLevels($request, $subcategory);
        $this->createUserGroups($request, $subcategory);
        $this->handleRequirements($request, $subcategory);
        $this->createFees($request, $subcategory);
        $this->createOrUpdateLimitation($request, $subcategory);
        $this->createOrUpdateComplaints($request, $subcategory);

        flash(t('Subcategory'), 'Subcategory has been saved', 'success');
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

        foreach ($request->get('levels', []) as $key => $role) {
            ApprovalLevels::create([
                'type' => 2,
                'level_id' => $subcategory->id,
                'role_id' => $role,
            ]);
        }

    }

    private function createUserGroups(Request $request, Subcategory $subcategory)
    {
        $subcategory->service_user_groups()->delete();

        foreach ($request->get('user_groups', []) as $group) {
            $subcategory->service_user_groups()->create([
                'level' => ServiceUserGroup::$SUBCATEGORY,
                'group_id' => $group
            ]);
        }
    }

    private function handleRequirements(Request $request, Subcategory $subcategory)
    {
        $subcategory->requirements()->delete();

        foreach ($request->get('requirements', []) as $requirement) {

            $subcategory->requirements()->create([
                'reference_type' => 2,
                'reference_id' => $subcategory->id,
                'field' => $requirement['field'],
                'operator' => 'is',
                'label' => $requirement['label'],
                'value' => $requirement['value'],
                'type' => $requirement['type'],
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


    private function createOrUpdateLimitation(Request $request, Subcategory $subcategory)
    {
        $subcategory->limitations()->delete();

        foreach ($request->get('limitations', []) as $limitation) {
            $subcategory->limitations()->create([
                'value' => explode(",", $limitation['value']),
                'label' => $limitation['label'],
                'level' => get_class($subcategory),
                'number_of_tickets' => $limitation['number_of_tickets'],
            ]);
        }

    }
    private function createOrUpdateComplaints(Request $request, Subcategory $subcategory)
    {
        $subcategory->complaint()->delete();

        Complaint::create([
            'level_id' => $subcategory->id,
            'level' => 'App\Subcategory',
            'to' => $request->complaint['to'] ?? [],
            'cc' => $request->complaint['cc'] ?? []
        ]);
    }
}
