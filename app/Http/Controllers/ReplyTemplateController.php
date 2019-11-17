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
        $this->validate($request, ['title' => 'required', 'description' => 'required']);
        $request['user_id'] = auth()->user()->id;
        ReplyTemplate::create($request->all());

        return redirect()->route('reply_template.index');
    }

    function update(ReplyTemplate $reply_template, Request $request)
    {
        $this->validate($request, ['title' => 'required', 'description' => 'required']);
        $reply_template->update($request->all());

        return redirect()->route('reply_template.index');
    }

    function destroy(ReplyTemplate $reply_template){
        $reply_template->delete();
        return redirect()->route('reply_template.index');
    }
}
