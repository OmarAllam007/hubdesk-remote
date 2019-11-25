<?php

namespace KGS\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KGSAdminController extends Controller
{
    function category(Category $category)
    {
        return view("kgs::admin.category.edit", compact('category'));
    }
}
