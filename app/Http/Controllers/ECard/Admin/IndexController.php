<?php

namespace App\Http\Controllers\ECard\Admin;

use App\BusinessCardUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    function index()
    {
        $users = BusinessCardUser::orderBy('name')->paginate();
        return view('e-card.admin.user.index',compact('users'));
    }
}
