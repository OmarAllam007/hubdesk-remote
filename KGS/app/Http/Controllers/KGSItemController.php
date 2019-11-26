<?php

namespace KGS\Http\Controllers;

use App\AdditionalFee;
use App\ApprovalLevels;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use KGS\Requirement;

class KGSItemController extends Controller
{

    function index()
    {
        $items = Item::whereHas('subcategory.category', function ($q) {
            return $q->where('business_unit_id', env('GS_ID'));
        })->paginate();

        return view("kgs::admin.item.index", compact('items'));
    }

    function edit(Item $item)
    {
        return view("kgs::admin.item.edit", compact('item'));
    }

    function create()
    {
        return view('kgs::admin.item.create');
    }

    function store(Request $request){
        $this->validate($request, ['subcategory_id' => 'required', 'name' => 'required', 'description' => 'required'],
            ['subcategory_id.required'=>'The category field is required.']);

        $item = Item::create($request->all());
        $this->handleLevels($request, $item);
        $this->handleRequirements($request, $item);
        $this->createFees($request, $item);

        flash(t('Item has been saved'), 'success');
        return \Redirect::route('kgs.admin.item.index');
    }

    function update(Item $item, Request $request)
    {
        $this->handleLevels($request, $item);
        $this->handleRequirements($request, $item);
        $this->createFees($request, $item);

        $data = $request->all();

        $item->update($data);

        flash(t('Item has been saved'), 'success');
        return \Redirect::route('kgs.admin.item.index');

    }

    function show(Item $item)
    {

        return view("kgs::admin.item.show", compact('item'));
    }


    function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('kgs.admin.item.index');
    }

    private function handleLevels(Request $request, Item $item)
    {
        $item->levels()->delete();

        if (empty($request->levels)) {
            return;
        }

        foreach ($request->levels as $key => $role) {
            ApprovalLevels::create([
                'type' => ApprovalLevels::ITEM_TYPE,
                'level_id' => $item->id,
                'role_id' => $role,
            ]);
        }

    }

    private function handleRequirements(Request $request, Item $item)
    {
        $item->requirements()->delete();

        if (empty($request->requirements)) {
            return;
        }

        foreach ($request->requirements as $requirement) {
            $item->requirements()->create([
                'reference_type' => Requirement::ITEM_TYPE,
                'reference_id' => $item->id,
                'field' => $requirement['field'],
                'operator' => 'is',
                'label' => $requirement['label'],
                'value' => $requirement['value'],
                'type' => $requirement['type']
            ]);
        }

    }


    private function createFees(Request $request, Item $item)
    {
        $item->fees()->delete();
        if (empty($request->fees)) {
            return;
        }

        foreach ($request->fees as $fee) {
            $item->fees()->create([
                'name' => $fee['name'],
                'cost' => $fee['cost'],
                'level' => AdditionalFee::ITEM,
                'level_id' => $item->id,
            ]);
        }
    }
}
