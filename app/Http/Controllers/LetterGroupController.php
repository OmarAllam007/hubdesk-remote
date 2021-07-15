<?php

namespace App\Http\Controllers;

use App\Letter;
use App\LetterGroup;
use Illuminate\Http\Request;

class LetterGroupController extends Controller
{
    function index()
    {
        $groups = LetterGroup::orderBy('name')->paginate();
        return view('letters_admin.group.index', compact('groups'));
    }

    function create()
    {
        return view('letters_admin.group.create');

    }

    function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        LetterGroup::create($request->all());

        return redirect()->route('letters.letter-group.index');
    }

    function edit(LetterGroup $letter_group)
    {
        return view('letters_admin.group.edit', compact('letter_group'));
    }

    function update(LetterGroup $letter_group, Request $request)
    {
        $letter_group->update($request->all());
        return redirect()->route('letters.letter-group.index');
    }

    function show(LetterGroup $letter_group)
    {
        return view('letters_admin.group.show', compact('letter_group'));
    }

    function destroy(LetterGroup $letter_group)
    {
        $letter_group->delete();
        return redirect()->route('letters.letter-group.index');
    }
}
