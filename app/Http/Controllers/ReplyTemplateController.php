<?php

namespace App\Http\Controllers;

use App\ReplyTemplate;
use Illuminate\Http\Request;

class ReplyTemplateController extends Controller
{
    function index()
    {
        $templates = auth()->user()->reply_templates()->paginate(10);
        return view('user.configurations.reply_template.index', compact('templates'));
    }

    function create()
    {
        return view('user.configurations.reply_template.create');
    }

    function edit(ReplyTemplate $reply_template)
    {
        return view('user.configurations.reply_template.edit', compact('reply_template'));
    }

    function store(Request $request)
    {
        $this->validate($request,['title'=>'required','description'=>'required']);

        ReplyTemplate::create($request->all());
        return redirect()->to('reply_template.index');
    }

    function update(){

    }
}
