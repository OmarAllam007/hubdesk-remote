<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\TicketFieldResource;
use App\Item;
use App\Subcategory;
use Illuminate\Http\Request;

use App\Http\Requests;

class CustomFieldsController extends Controller
{
    function render(Request $request)
    {
        $category = $subcategory = $item = null;
        if ($request->has('category')) {
            $category = Category::find($request->get('category'));
        }

        if ($request->has('subcategory')) {
            $subcategory = Subcategory::find($request->get('subcategory'));
        }

        if ($request->has('item')) {
            $item = Item::find($request->get('item'));
        }

        return view('custom-fields.render', compact('category', 'subcategory', 'item'));
    }

    function getFields(Request $request)
    {
        $category = $subcategory = $item = null;
        $categories = collect();
        $subcategories = collect();
        $items = collect();

        if ($request->has('category')) {
            $category = Category::find($request->get('category'));
            $categories = TicketFieldResource::collection($category->custom_fields)->collection->sortBy('label')->groupBy('label');
        }

        if ($request->has('subcategory') && $request->get('subcategory') != '') {
            $subcategory = Subcategory::find($request->get('subcategory'));
            $subcategories = TicketFieldResource::collection($subcategory->custom_fields)->collection->sortBy('label')->groupBy('label');
        }

        if ($request->has('item') && $request->get('item') != '') {
            $item = Item::find($request->get('item'));
            $items = TicketFieldResource::collection($item->custom_fields)->collection->sortBy('label')->groupBy('label');
        }

        return $categories->toBase()->merge($subcategories->toBase())->merge($items->toBase())->toArray();
    }
}
