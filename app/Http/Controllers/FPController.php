<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Rats\Zkteco\Lib\ZKTeco;

class FPController extends Controller
{
    private $zk = '';
    public function __construct()
    {
        //el fp el ta7t
        if(auth()->id() == 59){
            $this->zk = new ZKTeco('192.168.110.240', 4370);
        }else{
            //fp IT office
            $this->zk = new ZKTeco('192.168.120.115', 4370);
        }
    }

    function index()
    {
        if (in_array(\Auth::user()->id, [1021,799, 7159, 655, 959,59])) {
//           $this->zk = new ZKTeco('192.168.110.240', 4370);

            $this->zk->connect();
            $this->zk->enableDevice();

            $currentTime = $this->zk->getTime();
            $currentAttendance = $this->zk->getAttendance();
            $this->zk->disconnect();
            return view('admin.fp.index', ['time' => $currentTime, 'attendance' => $currentAttendance]);
        } else {
            return "Not authorized";
        }

    }

    function postFP(Request $request)
    {
        if (in_array(\Auth::user()->id, [1021,799, 7159, 655, 959,59])) {

            $date = $request->get('date');

            if (!$date) {
                return redirect()->back();
            }
            $carbonDate = Carbon::parse($date)->format('Y-m-d H:i:s');
            $this->addFP($carbonDate);

            return redirect()->back();
        } else {
            return "Not authorized";
        }
    }

    private function addFP($date)
    {
        $this->zk = new ZKTeco('192.168.120.115', 4370);
        $this->zk->connect();
        $this->zk->enableDevice();

        $str = "";
        $str .= $date;

        $this->zk->setTime($str);

        $currentTime = $this->zk->getTime();
        $currentAttendance = $this->zk->getAttendance();
        $this->zk->disconnect();
        return view('admin.fp.index', ['time' => $currentTime, 'attendance' => $currentAttendance]);

    }
}
