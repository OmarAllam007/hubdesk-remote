<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use App\ServiceUserGroup;
use App\SubItem;
use Illuminate\Http\Request;

class SubItemController extends Controller
{

    protected $rules = ['name' => 'required', 'item_id' => 'required|exists:items,id', 'description' => 'max:500'];

    public function index()
    {
        $subItems = SubItem::paginate();

        return view('admin.subItem.index', compact('subItems'));
    }

    public function create(Request $request)
    {
        $item = Item::find($request->get('item',[]));
        return view('admin.subitem.create',compact('item'));
    }
//
    public function store(Request $request)
    {
        $this->validate($request,$this->rules);

        $subItem = SubItem::create($request->all());

        $this->createUserGroups($request, $subItem);
        $this->createOrUpdateLimitation($request, $subItem);

        if ($request->hasFile('logo')) {
            $logo_path = SubItem::uploadAttachment('sub_items', $subItem, $request->logo);
            $subItem->update(['logo' => $logo_path]);
        }

        flash(t('SubItem Info'),t('SubItem has been saved'), 'success');

        return \Redirect::route('admin.item.show', $subItem->item_id);
    }

    public function edit(SubItem $subItem)
    {
        return view('admin.subitem.edit', compact('subItem'));
    }
//
    public function update(SubItem $subItem, Request $request)
    {
        $subItem->update($request->all());
        $this->createUserGroups($request, $subItem);
        $this->createOrUpdateLimitation($request, $subItem);

        if ($request->hasFile('logo')) {
            $logo_path = SubItem::uploadAttachment('sub_items', $subItem, $request->logo);
            $subItem->update(['logo' => $logo_path]);
        }


        flash(t('SubItem Info'),'SubItem has been saved', 'success');
        return \Redirect::route('admin.item.show', $subItem->item_id);
    }
//
    public function destroy(SubItem $subItem)
    {
        $subItem->delete();

        flash(t('subItem Info'),t('subItem has been deleted'), 'success');

        return \Redirect::route('admin.item.show', $subItem->item_id);
    }
//
//    private function handleLevels(Request $request,Item $item)
//    {
//        $item->levels()->delete();
//
//        if (!empty($request->levels)) {
//            foreach ($request->levels as $key => $role) {
//                ApprovalLevels::create([
//                    'type' => 3,
//                    'level_id' => $item->id,
//                    'role_id' => $role,
//                ]);
//            }
//        }
//    }
//
    private function createUserGroups(Request $request,SubItem $subItem)
    {
        if (!empty($request['user_groups'])) {
            $subItem->service_user_groups()->delete();

            foreach ($request['user_groups'] as $group) {
                $subItem->service_user_groups()->create([
                    'level' => ServiceUserGroup::$SUB_ITEM,
                    'group_id' => $group
                ]);
            }
        }
    }

    private function createOrUpdateLimitation(Request $request, SubItem $subItem)
    {
        $subItem->limitations()->delete();

        foreach ($request->get('limitations', []) as $limitation) {
            $subItem->limitations()->create([
                'value' => explode(",", $limitation['value']),
                'label' => $limitation['label'],
                'level' => get_class($subItem),
                'number_of_tickets' => $limitation['number_of_tickets'],
            ]);
        }

    }
}
