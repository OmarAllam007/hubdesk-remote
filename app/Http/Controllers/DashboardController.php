<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\Helpers\ChromePrint;
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
//        dd($data);
//        if($request->has('pdf')){
//            $content = view('dashboard.display', compact('data','businessUnit'));
//
//            $filepath = storage_path('app/' . uniqid('report') . '.html');
//            file_put_contents($filepath, $content->render());
//
//            $print = new ChromePrint($filepath);
//            $file = $print->print();
//
//            return $file;
//        }

        return view('dashboard.display', compact('data','businessUnit'));

    }


}
