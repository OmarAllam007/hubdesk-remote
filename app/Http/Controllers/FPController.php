<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

class FPController extends Controller
{
    function index()
    {
        $zk = new ZKTeco('192.168.120.115', 4370);
        $zk->connect();
        $zk->enableDevice();

        $currentTime = $zk->getTime();
        $currentAttendance = $zk->getAttendance();

        return view('admin.fp.index',['time'=> $currentTime,'attendance'=> $currentAttendance]);
    }

    function postFP(Request $request){
        $date = $request->get('date');

        if (!$date){
            return redirect()->back();
        }
        $carbonDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $this->addFP($carbonDate);
        return redirect()->back();
    }

    private function addFP($date)
    {
        $zk = new ZKTeco('192.168.120.115', 4370);
        $zk->connect();
        $zk->enableDevice();

        $str = "";
        $str.= $date;

        $zk->setTime($str);

        $currentTime = $zk->getTime();
        $currentAttendance = $zk->getAttendance();

        return view('admin.fp.index',['time'=> $currentTime,'attendance'=> $currentAttendance]);

    }
}
