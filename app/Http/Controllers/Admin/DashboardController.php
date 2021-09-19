<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.select_admin');
    }

    public function systemAdmin()
    {
        return view('admin.dashboard.index');
    }
}
