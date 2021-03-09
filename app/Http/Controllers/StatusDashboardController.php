<?php

namespace App\Http\Controllers;

use App\Helpers\ChromePrint;
use App\Helpers\dashboard\StatusDashboard;
use Illuminate\Http\Request;

class StatusDashboardController extends Controller
{
    function index()
    {
        if (\request()->has('print')) {
            $content = view('dashboard.status_dashboard.display');
            $filepath = storage_path('app/status_dashboard.html');
            file_put_contents($filepath, $content->render());
            $print = new ChromePrint($filepath);
            $file = $print->print();

            return response()->download($file, null, [], 'inline');
        }
        return view('dashboard.status_dashboard.display');
    }

    function getData(){
//        return \request('filters')
        return  response()->json(new StatusDashboard());
    }
}
