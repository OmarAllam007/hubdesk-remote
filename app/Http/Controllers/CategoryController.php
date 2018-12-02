<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

        protected $rules = ['name' => 'required', 'business_unit_id' => 'required|exists:businessUnits,id'];


    public function index()
    {
        $categories = Category::orderBy('name')->quickSearch()->paginate();;

        return view('category.index', compact('categories'));
    }

  
    public function show(Category $category)
    {
        return view('category.show', compact('category'));
    }

   
}
