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

    const PRECAST_IP = '192.168.150.241';
    const TOWER_G_FLOOR = '192.168.110.240';
    const IT_OFFICE = '192.168.120.115';

    public function __construct()
    {

    }

    function index()
    {
        if (\request('q') && \request('q') == 1) {
            $this->zk = new ZKTeco(self::PRECAST_IP, 4370);
        } else if (\request('q') && \request('q') == 2) {
            $this->zk = new ZKTeco(self::IT_OFFICE, 4370);
        } else if (\request('q') && \request('q') == 3) {
            $this->zk = new ZKTeco(self::TOWER_G_FLOOR, 4370);
        } else {
            return 'Error ...... Check your URL';
        }

        if (in_array(\Auth::user()->id, [1021, 799, 7159, 655, 959, 59])) {
            $this->zk->connect();
            $this->zk->enableDevice();
            $currentTime = $this->zk->getTime();

            $currentAttendance = $this->zk->getAttendance();
            return view('admin.fp.index', ['time' => $currentTime,
                'attendance' => $currentAttendance, 'ip' => $this->zk->_ip]);
        } else {
            return "Not authorized";
        }

    }

    function postFP(Request $request)
    {
        if (\request('q') && \request('q') == 1) {
            $this->zk = new ZKTeco(self::PRECAST_IP, 4370);
        } else if (\request('q') && \request('q') == 2) {
            $this->zk = new ZKTeco(self::IT_OFFICE, 4370);
        } else if (\request('q') && \request('q') == 3) {
            $this->zk = new ZKTeco(self::TOWER_G_FLOOR, 4370);
        } else {
            return 'Error ...... Check your URL';
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
