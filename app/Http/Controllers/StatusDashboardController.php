<?php

namespace App\Http\Controllers;

use App\Helpers\dashboard\StatusDashboard;
use Illuminate\Http\Request;

class StatusDashboardController extends Controller
{
    function index()
    {
        return view('dashboard.status_dashboard.display');
    }

    function getData(){
        return  response()->json(new StatusDashboard());
    }
}
