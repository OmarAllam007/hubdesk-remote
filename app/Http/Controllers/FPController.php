<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Rats\Zkteco\Lib\Helper\Util;
use Rats\Zkteco\Lib\ZKTeco;

class FPController extends Controller
{
    private $zk = '';

    public function __construct()
    {

    }

    function index()
    {
        if (auth()->check() && auth()->user()->employee_id == 90005016) {
            $this->zk = new ZKTeco('192.168.110.240', 4370);
        } else {
            $this->zk = new ZKTeco('192.168.120.115', 4370);
        }

        if (in_array(\Auth::user()->id, [1021, 799, 7159, 655, 959, 59])) {
            $this->zk->connect();
            $this->zk->enableDevice();
            $currentTime = $this->zk->getTime();

            $currentAttendance = $this->zk->getAttendance();
            return view('admin.fp.index', ['time' => $currentTime, 'attendance' => $currentAttendance,'ip'=> $this->zk->_ip]);
        } else {
            return "Not authorized";
        }

    }

    function postFP(Request $request)
    {
        if (auth()->check() && auth()->user()->employee_id == 90005016) {
            $this->zk = new ZKTeco('192.168.110.240', 4370);
        } else {
            $this->zk = new ZKTeco('192.168.120.115', 4370);
        }

        if (in_array(\Auth::user()->id, [1021, 799, 7159, 655, 959, 59])) {

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
        $this->zk->connect();
        $str = "";
        $str .= $date;

        $this->zk->setTime($str);

        $this->zk->enableDevice();

        $currentTime = $this->zk->getTime();
        $currentAttendance = $this->zk->getAttendance();
        $this->zk->disconnect();
        return view('admin.fp.index', ['time' => $currentTime, 'attendance' => $currentAttendance]);

    }
}
