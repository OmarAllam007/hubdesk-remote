<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\Category;
use App\Group;
use App\Http\Requests;
use App\Impact;
use App\Item;
use App\Location;
use App\Priority;
use App\Status;
use App\Subcategory;
use App\SubItem;
use App\Ticket;
use App\Urgency;
use App\User;
use KGS\BusinessDocumentsFolder;

class ListController extends Controller
{
    public function subcategory($cat_id = false)
    {
        $query = Subcategory::query()->active();
        if ($cat_id) {
            return $query->orderBy('name')->where('category_id', $cat_id)->get(['name', 'id']);
        }

        return $query->canonicalList();
    }

    public function item($subcat_id = false)
    {
        $query = Item::query()->active();

        if ($subcat_id) {
            return $query->orderBy('name')->where('subcategory_id', $subcat_id)->get(['name', 'id']);
        }

        return $query->canonicalList();
    }

    public function subitem($item_id = false)
    {
        $query = SubItem::query()->active();
        if ($item_id) {
            return $query->orderBy('name')->where('item_id', $item_id)->get(['name', 'id']);
        }

        return $query->canonicalList();
    }


    public function category($service_type = null)
    {
        $categories = Category::query()->active();

        if ($service_type == 1) {
            $categories->ticketType();
        } elseif ($service_type == 2) {
            $categories->taskType();
        } else {
            $categories->both();
        }

        return $categories->orderBy('order')->get(['id', 'name']);
    }


    public function folders($business_unit)
    {
        return BusinessDocumentsFolder::orderBy('name')
            ->where('business_unit_id', $business_unit)
            ->get(['name', 'id']);
    }

    public function tasksCategory()
    {
        return Category::orderBy('name')
            // ->where('type', Ticket::TASK_TYPE)
            ->get(['id', 'name']);
    }

    public function location()
    {
        return Location::orderBy('name')->get(['name', 'id']);
    }

    public function businessUnit()
    {
        return BusinessUnit::orderBy('name')->get(['name', 'id']);
    }

    public function priority()
    {
        return Priority::orderBy('name')->get(['name', 'id']);
    }

    public function urgency()
    {
        return Urgency::orderBy('name')->get(['name', 'id']);
    }

    public function impact()
    {
        return Impact::orderBy('name')->get(['name', 'id']);
    }

    public function supportGroup()
    {
        return Group::support()->orderBy('name')->get(['name', 'id']);
    }

    public function technician()
    {
        return User::active()->technicians()->orderBy('name')->get(['name', 'id']);
    }

    function requester()
    {
        return User::active()->orderBy('name')->get(['name', 'id']);
    }

    function status()
    {
        return Status::orderBy('name')->get(['name', 'id']);
    }

    function technicians($group = false)
    {
        $user_ids = \DB::table('group_user')->where('group_id', $group)->pluck('user_id');
        return User::active()->technicians()->whereIn('id', $user_ids)->orderBy('name')->get(['name', 'id']);
    }


    function kgs_category()
    {
        $categories = Category::query()->where('business_unit_id', 6)->corporate()->active();

        return $categories->orderBy('order')->get(['id', 'name']);
    }

    function kgs_subcategory()
    {
        $query = Subcategory::query()->whereHas('category', function ($q) {
            return $q->corporate();
        })->active()->canonicalList();


        return $query;
    }

    function kgs_item()
    {
        $query = Item::query()->whereHas('subcategory.category', function ($q) {
            return $q->corporate();
        })->active()->canonicalList();

        return $query;
    }

    function kgs_subitem()
    {
        $query = SubItem::query()->whereHas('item.subcategory.category', function ($q) {
            return $q->corporate();
        })->active()->canonicalList();

        return $query;
    }


    function approvers()
    {
        return User::active()->orderBy('name')->whereNotNull('email')->get(['name', 'id', 'email'])->map(function ($user) {
            return ['id' => $user->id, 'name' => $user->name . ' ( ' . $user->email . ' ) '];
        });
    }

    //define in options array like ["url":"","keys":{"key1":"key1",.....}]
    function fromFile($fileName)
    {
        $url = '/files/' . $fileName;
        $dataof = file_get_contents(url($url));
        $data = json_decode($dataof, true);

        $data = array_filter($data);
        $keys = request('keys');

        $data = collect($data)->filter()->where('type', 'airport')->whereNotNull('name')->map(function ($item) use ($keys) {
            $value = '';

            $keys_arr = explode(',', $keys);
            foreach ($keys_arr as $index => $key) {
                $value .= $item[$key];
                if ($index < count($keys_arr) - 1) {
                    $value .= ' - ';
                }
            }
            return $value;
        })->values()->toJson();

        return $data;
    }

}
