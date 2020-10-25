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

        if (!$request->has('print')) {
            session()->put('filters', $request->get('filters', []));
        }

        $data = new DashboardInfo(session('filters'), $businessUnit);

        if ($request->has('print')) {
            $content = view('dashboard.display', compact('data', 'businessUnit'));

            $filepath = storage_path('app/dashboard.html');
            file_put_contents($filepath, $content->render());
            $print = new ChromePrint($filepath);
            $file = $print->print();

            return response()->download($file, null, [], 'inline');
//            $headers = array(
//                'Content-Type: application/pdf',
//            );
////            return response()->download($file, strtolower(str_slug($businessUnit->name)) . '-dashboard.pdf', $headers);
//            return $file;
        }

        return view('dashboard.display', compact('data', 'businessUnit'));

    }


}
