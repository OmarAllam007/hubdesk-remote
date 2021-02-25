<?php

namespace App\Http\Controllers;

use App\Helpers\dashboard\StatusDashboard;
use Illuminate\Http\Request;

class StatusDashboardController extends Controller
{
    function index()
    {
        $data = new StatusDashboard();
        dd($data);
    }
}
