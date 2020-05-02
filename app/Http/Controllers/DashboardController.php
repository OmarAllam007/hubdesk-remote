<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\Helpers\dashboard\DashboardInfo;
use App\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function selectServiceUnit()
    {
        $this->authorize('dashboard');

        return view('dashboard.select_business_unit');
    }

    function display(BusinessUnit $businessUnit, Request $request)
    {
        $this->authorize('dashboard');

        $data = new DashboardInfo($request->get('filters', []),$businessUnit);
//        dd($data->ticketsByCoordinator);
        return view('dashboard.display', compact('data','businessUnit'));

    }
}
