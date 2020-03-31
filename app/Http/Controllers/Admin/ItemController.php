<?php

namespace App\Http\Controllers\Admin;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\Item;
use App\ServiceUserGroup;
use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{

    protected $rules = ['name' => 'required', 'subcategory_id' => 'required|exists:subcategories,id', 'description' => 'max:500'];

    public function index()
    {
        $items = Item::paginate();

        return view('admin.item.index', compact('items'));
    }

    public function create(Request $request)
    {
        $subcategory_id = 0;
        if ($request->has('subcategory')) {
            $subcategory_id = $request->get('subcategory');
        }

        $item = new Item();
        return view('admin.item.create', compact('subcategory_id', 'item'));
    }

    public function store(Request $request)
    {
        $this->validates($request);
        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $item = Item::create($data);

        $this->handleLevels($request, $item);
        $this->createUserGroups($request, $item);
        $this->handleRequirements($request, $item);
        $this->createFees($request, $item);
        $this->createOrUpdateLimitation($request, $item);

        flash(t('Item Info'), t('Item has been saved'), 'success');
        return \Redirect::route('admin.subcategory.show', $item->subcategory_id);
    }

    public function show(Item $item)
    {
        return view('admin.item.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('admin.item.edit', compact('item'));
    }

    public function update(Item $item, Request $request)
    {
        $service_request = isset($request->service_request) ? 1 : 0;
        $data = $request->all();
        $data['service_request'] = $service_request;
        $item->update($data);

        $this->handleLevels($request, $item);
        $this->createUserGroups($request, $item);
        $this->handleRequirements($request, $item);
        $this->createFees($request, $item);
        $this->createOrUpdateLimitation($request, $item);

        flash(t('Item Info'), 'Item has been saved', 'success');

        return \Redirect::route('admin.subcategory.show', $item->subcategory_id);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        flash(t('Item Info'), t('Item has been deleted'), 'success');

        return \Redirect::route('admin.subcategory.show', $item->subcategory_id);
    }

    private function handleLevels(Request $request, Item $item)
    {
        $item->levels()->delete();

        foreach ($request->get('levels', []) as $key => $role) {
            ApprovalLevels::create([
                'type' => 3,
                'level_id' => $item->id,
                'role_id' => $role,
            ]);
        }
    }

    private function createUserGroups(Request $request, Item $item)
    {
        $item->service_user_groups()->delete();

        foreach ($request->get('user_groups',[]) as $group) {
            $item->service_user_groups()->create([
                'level' => ServiceUserGroup::$ITEM,
                'group_id' => $group
            ]);
        }
    }

    private function handleRequirements(Request $request, Item $item)
    {
        $item->requirements()->delete();

        foreach ($request->get('requirements',[]) as $requirement) {
            $item->requirements()->create([
                'reference_type' => 3,
                'reference_id' => $item->id,
                'field' => $requirement['field'],
                'operator' => 'is',
                'label' => $requirement['label'],
                'value' => $requirement['value'],
                'type' => $requirement['type'],
            ]);
        }

    }

    private function createFees(Request $request, Item $item)
    {

        $item->fees()->delete();

        foreach ($request->get('fees',[]) as $fee) {
            $item->fees()->create([
                'name' => $fee['name'],
                'cost' => $fee['cost'],
                'level' => AdditionalFee::ITEM,
                'level_id' => $item->id,
            ]);
        }
    }


    private function createOrUpdateLimitation(Request $request, Item $item)
    {
        $item->limitations()->delete();

        foreach ($request->get('limitations', []) as $limitation) {
            $item->limitations()->create([
                'value' => explode(",", $limitation['value']),
                'label' => $limitation['label'],
                'level' => get_class($item),
                'number_of_tickets' => $limitation['number_of_tickets'],
            ]);
        }

    }
}
