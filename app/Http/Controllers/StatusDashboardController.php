<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\Helpers\ChromePrint;
use App\Helpers\dashboard\StatusDashboard;
use Illuminate\Http\Request;

class StatusDashboardController extends Controller
{
    function index(BusinessUnit $businessUnit)
    {
        $data = new StatusDashboard(session('status_filters'), $businessUnit);

        if (\request()->has('print')) {
            $content = view('dashboard.status_dashboard.display', compact('data', 'businessUnit'));
            $filepath = storage_path('app/status_dashboard.html');

            file_put_contents($filepath, $content->render());
            $print = new ChromePrint($filepath);
            $file = $print->print();
//            dd('asd');

            return response()->download($file, 'status_dashboard', [], 'inline');
        }


        return view('dashboard.status_dashboard.display', compact('data', 'businessUnit'));
    }

    function getData()
    {
        session()->put('status_filters', request()->get('filters', []));

        $businessUnit = BusinessUnit::find(\request('businessUnit'));
        return response()->json(new StatusDashboard(session('status_filters'), $businessUnit));
    }
}
