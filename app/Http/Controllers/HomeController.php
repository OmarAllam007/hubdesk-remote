<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Language;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ticket.create_ticket.index');
    }

    public function home()
    {
        $url = '/ticket/create';
        if (\Auth::guest()) {
            $url = '/login';
        }

        return \Redirect::to($url);
    }

    function changeLanguage($language)
    {
        \Session::forget('personalized-language' . \Auth::user()->id);

        \Session::put("personalized-language" . \Auth::user()->id, $language ?? Language::ENGLISH);
        return redirect()->back();
    }
}

