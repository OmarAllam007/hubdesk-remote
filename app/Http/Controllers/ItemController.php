<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;


class ItemController extends Controller
{

    protected $rules = ['name' => 'required', 'subcategory_id' => 'required|exists:subcategories,id', 'description' => 'max:500'];

    public function index()
    {
        $items = Item::paginate();

        return view('item.index', compact('items'));
    }

  
    public function show(Item $item)
    {
        return view('item.show', compact('item'));
    }

  
}
