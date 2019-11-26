<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    function index(){
        return view('user.configurations.index');
    }
}
