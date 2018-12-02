<?php

namespace App\Http\Controllers;

use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubcategoryController extends Controller
{

    protected $rules = ['name' => 'required', 'category_id' => 'required|exists:categories,id'];

    public function index()
    {
        $subcategories = Subcategory::paginate();

        return view('subcategory.index', compact('subcategories'));
    }

    public function show(Subcategory $subcategory)
    {
        return view('subcategory.show', compact('subcategory'));
    }

}
